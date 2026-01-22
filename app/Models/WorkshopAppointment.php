<?php

// app/Models/WorkshopAppointment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopAppointment extends Model
{
    protected $fillable = [
        'license_plate','mileage',
        'maintenance_option','extra_services',
        'appointment_date','appointment_time','wait_while_service',
        'company_name','salutation','first_name','middle_name','last_name',
        'street','house_number','addition','postal_code','city',
        'phone','email','remarks',
        'terms_accepted','marketing_opt_in','status',
    ];

    protected $casts = [
        'extra_services' => 'array',
        'wait_while_service' => 'boolean',
        'terms_accepted' => 'boolean',
        'marketing_opt_in' => 'boolean',
        'appointment_date' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->middle_name.' '.$this->last_name);
    }
}
