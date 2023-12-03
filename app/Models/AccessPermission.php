<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPermission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_access_permissions');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_access_permissions');
    }
    
    
}