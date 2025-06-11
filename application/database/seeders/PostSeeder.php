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
            [
                'content' => "L'importance de la collaboration transfrontalière ne peut pas être sous-estimée. C'est grâce à elle que nous pouvons progresser ensemble. 🌍",
            ],
            [
                'content' => "Nouvelle publication en ligne ! Venez découvrir nos dernières recherches sur la médecine regenerative. #Médecine #Innovation",
            ],  
            [
                'content' => "Merci à tous pour vos retours positifs sur notre dernière publication. C'est motivant de voir que notre travail aide la communauté ! 📚",
            ],
            [
                'content' => "L'importance de la collaboration transfrontalière ne peut pas être sous-estimée. C'est grâce à elle que nous pouvons progresser ensemble. 🌍",
            ],
            
            [
                'content' => "Merci à tous pour vos retours positifs sur notre dernière publication. C'est motivant de voir que notre travail aide la communauté ! 📚",
            ],
             [
        'content' => "Quelqu’un a trouvé un trousseau de clés avec un porte-clé rouge près de la cafétéria ? 🙏 Merci de me contacter si oui.",
    ],
    [
        'content' => "⚠️ J’ai perdu ma carte d’étudiant ce matin entre l’amphi B et la bibliothèque. Nom : Karim El Yazidi. Merci de me prévenir si vous la trouvez !",
    ],
    [
        'content' => "Club Tech Euromed recrute ! Si tu t'intéresses au dev web, mobile ou à l’IA, rejoins-nous pour construire des projets concrets 💻✨ #EIDIA",
    ],
    [
        'content' => "Le club musique prépare une soirée open mic ce vendredi ! 🎤🎸 Viens partager tes vibes à l’amphi A – ambiance chill garantie ! #VieÉtudiante",
    ],
    [
        'content' => "Une grosse pensée aux étudiants INSA qui terminent leur soutenance cette semaine. Courage à tous, vous êtes presque au bout ! 💪📊",
    ],
    [
        'content' => "Nouvelle exposition d’art à la FLSH sur les identités méditerranéennes. À ne pas rater pour les amateurs de culture ! 🎨🌊",
    ],
    [
        'content' => "Je suis fier de faire partie de cette université où l’innovation, la diversité et l’ouverture sont au cœur de la formation. #EuromedSpirit",
    ],
    [
        'content' => "EIDIA 4A : le projet de Gantt touche à sa fin, big up à tout le groupe 2 pour la gestion au top. 📈⏳",
    ],
    [
        'content' => "On a eu un débat passionnant aujourd’hui en cours de droit international. C’est ça qu’on aime à Euromed : penser, échanger, évoluer. ⚖️🌐",
    ],
    [
        'content' => "Une bouteille réutilisable noire oubliée dans la salle B1-03 ? Elle est à l’accueil depuis ce matin.",
    ],
    [
        'content' => "Les inscriptions pour le hackathon inter-écoles sont ouvertes ! 48h pour innover et prototyper. Représente ton école ! #HackEuromed 🧠🚀",
    ],
    [
        'content' => "Félicitations à l’équipe de basket pour leur victoire contre l’ENCG ! 🔥🏀 Prochaine étape : les régionales ! #EspritCompétition",
    ],
    [
        'content' => "Le club théâtre recrute de nouveaux talents pour sa pièce de fin d’année. Si t’aimes la scène, fonce 🎭🔥 #Culture",
    ],
    [
        'content' => "J’ai trouvé une clé USB rose près des distributeurs. Je la garde avec moi pour l’instant. MP si c’est la tienne.",
    ],
    [
        'content' => "Conférence demain sur le changement climatique avec des intervenants de haut niveau. Rendez-vous à l’auditorium à 10h 🌍 #GreenCampus",
    ],
    [
        'content' => "Partage d’une super ressource pour ceux qui préparent le TOEIC – PDF + tips gratos ici 📘💡 #Langues",
    ],
    [
        'content' => "J’ai adoré le dernier séminaire sur l'entrepreneuriat social. Bravo à tous les organisateurs 🙌 #BusinessSchool",
    ],
    [
        'content' => "Besoin d’aide en JavaScript ? On organise une session d’entraide au Learning Center ce jeudi à 16h 🧠💻 #CodingTogether",
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
            "Tu as bien résumé le sujet, bravo !",
            "J'apprécie ton point de vue, c'est très pertinent.",
            "Je n'avais jamais vu ça sous cet angle, merci !",
            "Beau travail, continue à publier ce genre de contenu.",
            "Ce genre de contenu mérite plus de visibilité.",
            "Je partage à 100% ton opinion.",
            "Très bien expliqué, c'est clair et précis.",
            "Tu m'as appris quelque chose aujourd'hui.",
            "Ce post m’a vraiment aidé, merci !",
            "J’attendais justement une info comme celle-là.",
            "C’est toujours un plaisir de lire tes posts.",
            "Franchement, chapeau pour cette publication.",
            "Je reviendrai relire ça plus tard, top !",
            "Ça m’a donné envie de creuser le sujet.",
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
