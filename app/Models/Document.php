<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'encrypted_content',
        'uuid'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}