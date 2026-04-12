<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceRecordFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'service_date',
        'remarks',
    ];

    protected $casts = [
        'service_date' => 'date'
    ];

    // Relationship
    
    public function appointment()
    {
        return $this->belongsTo(Appointment::class,'appointment_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class,'staff_id');
    }
}
