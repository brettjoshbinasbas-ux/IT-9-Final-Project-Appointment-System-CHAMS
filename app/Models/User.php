<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Appointment;
use App\Models\ServiceRecord;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    // cast
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Const Mapping
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_RECEPTIONIST = 'receptionist';

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_STAFF => 'Staff',
        self::ROLE_RECEPTIONIST => 'Receptionist',
    ];

    // Relationship
    public function assignedAppointments()
    {
        return $this->hasMany(Appointment::class, 'staff_id');
    }

    public function createdAppointments()
    {
        return $this->hasMany(Appointment::class, 'created_by');
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class, 'staff_id');
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role == 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role == 'staff';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }
}
