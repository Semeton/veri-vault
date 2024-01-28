<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'message_uuid', 'chat_id', 'status'];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
