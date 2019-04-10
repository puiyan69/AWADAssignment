<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PatientResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
        ];
    }
}
