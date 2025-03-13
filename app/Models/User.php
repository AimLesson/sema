<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'organisasi_id',
        'departement_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organisasi()
    {
        return $this->belongsTo(Organisasi::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

        /**
     * Check if user has a specific role
     */
    // public function hasRole($roleName): bool
    // {
    //     return $this->role && $this->role->name === $roleName;
    // }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->name, $roles);
    }


    public function hasRole($roleName): bool
    {
        return $this->role && strtolower($this->role->name) === strtolower($roleName);
    }
}
