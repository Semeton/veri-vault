<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'sender_email', 'recipient_email', 'status'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_email', 'email');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_email', 'email');
    }
}
