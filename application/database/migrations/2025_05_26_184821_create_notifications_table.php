<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui reçoit la notification
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui a déclenché la notification
            $table->string('type'); // 'follow', 'like', 'comment', 'new_post'
            $table->text('message'); // Message de la notification
            $table->json('data')->nullable(); // Données supplémentaires (post_id, comment_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
