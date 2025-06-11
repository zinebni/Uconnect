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
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter un index unique pour éviter les notifications dupliquées
            // Combinaison de user_id, from_user_id, type et created_at (à la minute près)
            $table->index(['user_id', 'from_user_id', 'type', 'created_at'], 'notifications_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_unique_idx');
        });
    }
};
