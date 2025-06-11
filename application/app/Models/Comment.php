<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'parent_id',
        'depth',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Relation avec le commentaire parent (pour les réponses)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relation avec les réponses (commentaires enfants)
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Relation avec les likes du commentaire
     */
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Vérifier si l'utilisateur connecté a liké ce commentaire
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Obtenir le nombre de likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Obtenir le nombre de réponses
     */
    public function getRepliesCountAttribute()
    {
        return $this->replies()->count();
    }

    /**
     * Scope pour obtenir seulement les commentaires principaux (pas les réponses)
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope pour obtenir les commentaires avec leurs réponses
     */
    public function scopeWithReplies($query)
    {
        return $query->with(['replies.user', 'replies.likes']);
    }
}
