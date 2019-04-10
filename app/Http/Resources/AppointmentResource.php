<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AppointmentResource extends Resource
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
            'id' => $this->when(!is_null($this->id), $this->id),
            'appDate' => $this->when(!is_null($this->appDate), $this->appDate),
            'appTime' => $this->when(!is_null($this->appTime), $this->appTime),
            'appReason' => $this->when(!is_null($this->appReason), $this->appReason),
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'patient' => new PatientResource($this->whenLoaded('patient')),
        ];
    }
}
