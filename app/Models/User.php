<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // implements MustVerifyEmail
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'current_team_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function apiKey(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function encryptedEmails(): HasMany
    {
        return $this->hasMany(EncryptedEmail::class);
    }

    public function sentChatRequests(): HasMany
    {
        return $this->hasMany(ChatRequest::class, 'sender_email', 'email');
    }

    public function receivedChatRequests(): HasMany
    {
        return $this->hasMany(ChatRequest::class, 'recipient_email', 'email');
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'sender_id')
                    ->orWhere('recipient_id', $this->id);
    }
}