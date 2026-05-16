<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Appointment;
use App\Models\ServiceRecord;
use App\Models\AppointmentHistory;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
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

    // Relationships
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

    public function histories()
    {
        return $this->hasMany(AppointmentHistory::class, 'changed_by');
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    // Check if user is active (not soft deleted)
    public function isActive(): bool
    {
        return is_null($this->deleted_at);
    }
}
