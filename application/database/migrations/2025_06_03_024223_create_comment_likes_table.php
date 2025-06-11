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
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Empêcher qu'un utilisateur like plusieurs fois le même commentaire
            $table->unique(['user_id', 'comment_id']);

            // Index pour les performances
            $table->index(['comment_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};
