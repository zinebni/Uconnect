<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'content' => "Je suis ravi de rejoindre la communauté Euromed ! J'ai hâte de partager mes expériences avec vous tous. 🌟",
            ],
            [
                'content' => "Magnifique journée pour une conférence sur l'innovation médicale. Les échanges ont été très enrichissants. #Médecine #Innovation",
            ],
            [
                'content' => "Nouveau projet passionnant en perspective ! La collaboration entre les différents pays méditerranéens est vraiment inspirante. 🌍",
            ],
            [
                'content' => "Partage de connaissances et d'expériences : c'est ça l'esprit Euromed ! Merci à tous pour ces échanges constructifs. 🤝",
            ],
            [
                'content' => "La recherche avance ! Heureux de voir tant de progrès dans notre domaine. Continuons ensemble ! 🔬",
            ],
            [
                'content' => "Belle réunion aujourd'hui avec nos partenaires internationaux. L'avenir s'annonce prometteur ! 🌟",
            ],
            [
                'content' => "La formation continue est essentielle dans notre métier. Ravi d'avoir participé à ce workshop enrichissant. #Formation #Médecine",
            ],
            [
                'content' => "Merci à tous pour vos retours positifs sur notre dernière publication. C'est motivant de voir que notre travail aide la communauté ! 📚",
            ],
        ];

        $users = User::all();

        foreach ($posts as $postData) {
            $user = $users->random();
            $post = new Post();
            $post->user_id = $user->id;
            $post->content = $postData['content'];
            $post->save();

            // Ajouter des likes aléatoires
            $likingUsers = $users->except($user->id)->random(rand(1, 4));
            foreach ($likingUsers as $likingUser) {
                $post->likes()->create([
                    'user_id' => $likingUser->id
                ]);
            }

            // Ajouter des commentaires aléatoires
            $comments = [
                "Excellent post ! Merci pour le partage. 👍",
                "Très intéressant, j'aimerais en savoir plus !",
                "Complètement d'accord avec toi !",
                "Super initiative ! Continue comme ça !",
                "C'est exactement ce dont nous avions besoin !",
                "Merci pour ces informations précieuses. 🙏",
            ];

            $commentingUsers = $users->except($user->id)->random(rand(1, 3));
            foreach ($commentingUsers as $commentingUser) {
                $post->comments()->create([
                    'user_id' => $commentingUser->id,
                    'content' => $comments[array_rand($comments)]
                ]);
            }
        }
    }
}
