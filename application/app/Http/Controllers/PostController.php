<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Events\PostCreated;
use App\Events\PostLiked;
use App\Events\PostCommented;
use App\Services\FriendSuggestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PostController extends Controller
{
   public function index(FriendSuggestionService $friendSuggestionService)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Forcer le retour en collection Laravel (plutôt qu’un array brut)
    $followingIds = $user->following()->pluck('following_id')->toBase();
    $followingIds->push($user->id);

    $posts = Post::with([
            'user',
            'likes',
            'comments' => function($query) {
                $query->whereNull('parent_id') // Seulement les commentaires principaux
                      ->with(['user', 'likes', 'replies.user', 'replies.likes'])
                      ->orderBy('created_at', 'desc');
            }
        ])
        ->whereIn('user_id', $followingIds)
        ->latest()
        ->paginate(10);

    // Données pour la sidebar de notifications
    try {
        $notifications = $user->notifications()
            ->with('fromUser')
            ->latest()
            ->limit(10)
            ->get();

        $friendSuggestions = $friendSuggestionService->getSuggestions(5);
        $friendsActivity = $friendSuggestionService->getFriendsRecentActivity(5);
    } catch (\Exception $e) {
        // En cas d'erreur, utiliser des collections vides
        $notifications = collect();
        $friendSuggestions = collect();
        $friendsActivity = collect();
    }

    return view('home', compact('posts', 'notifications', 'friendSuggestions', 'friendsActivity'));
}

    /**
     * Afficher un post spécifique
     */
    public function show(Post $post)
    {
        // Charger les relations nécessaires avec les réponses
        $post->load([
            'user',
            'likes',
            'comments' => function($query) {
                $query->whereNull('parent_id') // Seulement les commentaires principaux
                      ->with(['user', 'likes', 'replies.user', 'replies.likes'])
                      ->orderBy('created_at', 'desc');
            }
        ]);

        // Vérifier que l'utilisateur peut voir ce post
        // (soit c'est son post, soit il suit l'auteur, soit le post est public)
        $user = Auth::user();
        $canView = $post->user_id === $user->id ||
                   $user->following()->where('following_id', $post->user_id)->exists();

        if (!$canView) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce post.');
        }

        return view('posts.show', compact('post'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without_all:image,video|string|nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,webm,ogg|max:20480'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;

        if ($request->hasFile('image')) {
            try {
                $imagePath = storage_path('app/public/posts/images');
                if (!File::exists($imagePath)) {
                    File::makeDirectory($imagePath, 0755, true);
                }

                $file = $request->file('image');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

                Storage::disk('public')->putFileAs('posts/images', $file, $filename);
                $post->image_path = 'posts/images/' . $filename;
            } catch (\Exception $e) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'Erreur téléchargement image: '.$e->getMessage()], 400);
                }
                return redirect()->back()->with('error', 'Erreur lors du téléchargement de l\'image: ' . $e->getMessage());
            }
        }

        if ($request->hasFile('video')) {
            try {
                $videoPath = storage_path('app/public/posts/videos');
                if (!File::exists($videoPath)) {
                    File::makeDirectory($videoPath, 0755, true);
                }

                $file = $request->file('video');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

                Storage::disk('public')->putFileAs('posts/videos', $file, $filename);
                $post->video_path = 'posts/videos/' . $filename;
            } catch (\Exception $e) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'error' => 'Erreur téléchargement vidéo: '.$e->getMessage()], 400);
                }
                return redirect()->back()->with('error', 'Erreur lors du téléchargement de la vidéo: ' . $e->getMessage());
            }
        }

        $post->save();

        // Déclencher l'événement PostCreated pour notifier les followers
        PostCreated::dispatch(Auth::user(), $post);

        if ($request->wantsJson()) {
            // recharger le post avec relations pour le front
            $post->load(['user', 'likes', 'comments']);
            return response()->json(['success' => true, 'post' => $post]);
        }

        return redirect()->back()->with('success', 'Post créé avec succès !');
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => 'Action non autorisée'], 403);
            }
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        if ($post->video_path) {
            Storage::disk('public')->delete($post->video_path);
        }

        $post->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Post supprimé avec succès.');
    }

    public function like(Request $request, Post $post)
    {
        $userId = Auth::id();
        $isLiked = $post->likes()->where('user_id', $userId)->exists();

        if (!$isLiked) {
            $post->likes()->create(['user_id' => $userId]);

            // Déclencher l'événement PostLiked pour créer une notification
            PostLiked::dispatch(Auth::user(), $post);
            $liked = true;
        } else {
            $liked = false;
        }

        // Toujours retourner du JSON pour les requêtes AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $post->likes()->count(),
                'message' => $liked ? 'Post aimé !' : 'Vous avez déjà aimé ce post'
            ]);
        }

        return redirect()->back();
    }

    public function unlike(Request $request, Post $post)
    {
        $userId = Auth::id();

        $deleted = $post->likes()->where('user_id', $userId)->delete();

        // Toujours retourner du JSON pour les requêtes AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'liked' => false,
                'likes_count' => $post->likes()->count(),
                'message' => $deleted ? 'Like retiré' : 'Vous n\'aviez pas aimé ce post'
            ]);
        }

        return redirect()->back();
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        // Déclencher l'événement PostCommented pour créer une notification
        PostCommented::dispatch(Auth::user(), $post, $comment);

        // Toujours retourner du JSON pour les requêtes AJAX
        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'comments_count' => $post->comments()->count(),
                'message' => 'Commentaire ajouté avec succès !'
            ]);
        }

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Récupérer la liste des utilisateurs qui ont aimé un post
     */
    public function getLikes(Request $request, Post $post)
    {
        try {
            // Récupérer les utilisateurs qui ont aimé le post avec leurs informations
            $likes = $post->likes()
                ->with('user:id,name,profile_image')
                ->latest()
                ->get()
                ->map(function ($like) {
                    return [
                        'id' => $like->user->id,
                        'name' => $like->user->name,
                        'profile_image' => $like->user->profile_image,
                        'profile_image_url' => $like->user->profile_image
                            ? Storage::url($like->user->profile_image)
                            : null,
                        'liked_at' => $like->created_at->diffForHumans(),
                        'is_following' => Auth::user()->following()->where('following_id', $like->user->id)->exists()
                    ];
                });

            // Toujours retourner du JSON pour cette route
            return response()->json([
                'success' => true,
                'likes' => $likes,
                'total' => $likes->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getLikes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyComment(Request $request, Post $post, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'error' => 'Action non autorisée'], 403);
            }
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $comment->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès.');
    }
}
