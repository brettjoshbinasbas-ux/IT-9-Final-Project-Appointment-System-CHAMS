<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    // Relationships

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function serviceRecord()
    {
        return $this->hasMany(ServiceRecord::class, 'client_id');
    }

    // Accessor

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
