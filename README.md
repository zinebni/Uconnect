# UConnecte – Réseau Social Universitaire Privé

**UConnecte** (contraction de *University Connecté*) est une plateforme sociale conçue exclusivement pour la communauté de l’Université Euromed de Fès. L’objectif est de créer un environnement numérique sécurisé et ciblé pour connecter étudiants, enseignants et staff autour de l’expérience universitaire.

---

## 🎯 Objectif

UConnecte reproduit l’expérience sociale d’un réseau comme Facebook, en l’adaptant aux besoins, valeurs et opportunités spécifiques du milieu universitaire. Avant UConnecte, les étudiants étaient isolés entre départements, et les réseaux sociaux classiques mélangeaient vie personnelle et professionnelle, compliquant la recherche d’événements et de profils académiques.

---

## 🚀 Fonctionnalités principales

- 🔒 Réseau 100% universitaire privé et sécurisé  
- 🛡️ Données localisées et non commercialisées  
- 🧭 Centralisation de la vie universitaire  
- 👥 Interaction sociale (like, commentaire, réponse, follow)  
- 📝 Publication de posts multimédia  
- 💬 Messagerie interne  
- 🔔 Notifications en temps réel (interactions, messages)  

---

## 🏗️ Architecture du Système

L’architecture est modulaire et découplée, assurant maintenabilité, performance et évolutivité.

### 🔧 Backend

- **Laravel 10.x** – Framework principal avec architecture MVC
- **Laravel Breeze** – Starter kit léger pour l’authentification (login, register, etc.)
- **Laravel Queues ** – Exécution des tâches en arrière-plan (notifications, envoi de mails)
- **Pusher (WebSocket)** – Notifications et messagerie en temps réel

### 🎨 Frontend

- **Blade Templates** – Génération dynamique des vues côté serveur
- **Tailwind CSS** – Framework utilitaire pour un design rapide, propre et responsive
- **Alpine.js** – Mini framework JS pour interactions simples (menus, toggles…)
- **Axios** – Requêtes HTTP asynchrones (ex : envoyer un commentaire sans recharger la page)

### 🗄️ Base de données & Cache

- **MySQL** – Stockage des données utilisateurs, posts, messages, etc.
---

## ⚙️ Environnement de Développement

- PHP >= 8.1  
- Composer  
- Node.js & NPM  
- MySQL  
- Laravel 10.x  
- Laravel Breeze  
- Pusher  
- Tailwind CSS  
- Alpine.js  
- Axios  

---

## 📸 Aperçu (Screenshots)

![creer compte](./screenshots/1_creer_compte.png)
![modifier votre profile](./screenshots/2_modifier_votre_profile.png)
![dashboard initial](./screenshots/3_creer_votre_premier_post.png)
![post](./screenshots/3-post.png)
![chercher vos amis](./screenshots/4_chercher_vos_amie.png)
![envoyez des messages à vos amis](./screenshots/6_envoyer_message.png)
![barre de notification](./screenshots/7_bare_de_notification_et_suggesion.png)
![page de notification](./screenshots/7_notifiaction_page.png)
![voir profile](./screenshots/8_voire_profile.png)
![suivre en retour](./screenshots/9_suivre_en_retour.png)
![boite de message](./screenshots/10_voir_messages.png)
![repondre sur message](./screenshots/11_rependre_sur_message.png)
![aimer un post et ajouter un commentaire](./screenshots/12_aimer_post_ajouter_comment.png)
![voir qui ont aimé un post](./screenshots/12_voir_qui-ont_aime_post.png)
![aimer un commentaire et repondre sur un commentaire](./screenshots/13_liker_rependre_sur_comment.png)
![partager un post](./screenshots/14_partager_post.png)
![dashboard](./screenshots/dashboard.png)
![dashboard_abonné](./screenshots/dashboard_abonné.png)
![dashboard_abonnement](./screenshots/dashboard_abonnement.png)
![show profile](./screenshots/show_profile.png)
![video post](./screenshots/video_post.png)
![zoom sur image du post](./screenshots/zoom_sur_image_du_post.png)
![logout](./screenshots/15_logout.png)
![login](./screenshots/login.png)
![passer en mode sombre](./screenshots/mood_dark1.png)

---

## 📑 Rapport & Présentation

Le rapport de projet et la présentation PowerPoint sont disponibles dans le dossier /docs du dépôt.

---

## ✅ Statut du projet

> 🟡 **En cours de développement** – Une version basique fonctionnelle a été réalisée, couvrant l’authentification, les publications, les interactions sociales de base et la messagerie.

### 🔜 Prochaines étapes prévues :

- 🔧 Approfondissement de la logique métier pour cadrer avec les besoins académiques
- 📚 Ajout de fonctionnalités clés :
  - Stories et contenu éphémère
  - Gestion des événements universitaires
  - Création de clubs, groupes de discussion et groupes académiques
  - Système de live & appels vidéo intégrés
- 🧱 Refonte technique :
  - Migration vers une architecture **microservices**
  - Mise en place de **GraphQL** pour optimiser les échanges client–serveur
  - Intégration d’un **CDN** pour accélérer le chargement des médias
---

## 👩‍💻 Auteur

**ZINEB BENNANI GABSI**  
Étudiante en ingénierie – Développement Web Full Stack  
Université Euromed de Fès  2024-2025

---

## 📄 Licence

Ce projet a été réalisé dans le cadre d’un module académique à l’Université Euromed de Fès.  
Il est fourni **à des fins pédagogiques uniquement**.

La réutilisation partielle du code est autorisée à des fins éducatives.

