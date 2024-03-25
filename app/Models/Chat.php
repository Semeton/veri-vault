<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        "sender_id",
        "recipient_id",
        "uuid",
        "chat_key",
        "sender_secret",
        "sender_lock_secret",
        "recipient_secret",
        "recipient_lock_secret",
        "archive",
        "status",
    ];

    protected $hidden = [
        "chat_key",
        // 'sender_secret',
        "sender_lock_secret",
        // 'recipient_secret',
        "recipient_lock_secret",
    ];

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
