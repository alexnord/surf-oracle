<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'timestamp' => $this->timestamp,
            'timezone' => $this->timezone,
            'text' => $this->text,
            'temperature' => $this->temperature,
            'angle' => $this->angle,
            'direction' => $this->direction,
            'speed' => $this->speed,
        ];
    }
}
