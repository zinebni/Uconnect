<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
    ];

    /**
     * Relation avec l'utilisateur qui a liké
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le commentaire liké
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
}
