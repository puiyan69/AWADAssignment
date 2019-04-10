<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = "doctors";

    protected $fillable = [
        'firstName', 
        'lastName',
        'specialization'
    ];

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
