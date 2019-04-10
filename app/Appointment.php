<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'appDate',
        'appTime',
        'appReason',
        'doctor_id',
        'patient_id',
    ];

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
