<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = "patients";
    
    protected $fillable = [
        'firstName',
        'lastName',
        'address',
        'city',
        'state'
    ];

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
