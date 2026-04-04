<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'staff_id',
        'service_type',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    // Constant Mapping

    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    const STATUSES = [
        self::STATUS_SCHEDULED => 'Scheduled',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_NO_SHOW => 'No Show',
    ];

    // Relationship

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class,'staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function serviceRecord()
    {
        return $this->hasOne(ServiceRecord::class,'appointment_id');
    }

    // Query Builder Scopes

    public function scopeToday(Builder $query)
    {
        return $query->whereDate('appointment_date',today());
    }

    public function scopeUpcoming(Builder $query)
    {
        return $query->whereDate('appointment_date','>=',today())
                     ->where('status','!=','cancelled')
                     ->orderBy('appointment_date')
                     ->orderBy('appointment_time');
    }
    
    public function scopeByStatus(Builder $query, string $status)
    {
        return $query->where('status',$status);
    }
}
