<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentAccessPermission extends Model
{
    use HasFactory;

    protected $fillable = ['document_id', 'access_permission_id'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    
    public function permission(): BelongsTo
    {
        return $this->belongsTo(AccessPermission::class);
    }
}