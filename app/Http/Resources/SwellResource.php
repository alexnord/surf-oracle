<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SwellResource extends JsonResource
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
            'surf_height_min' => $this->surfline_surf_height_min,
            'surf_height_max' => $this->surfline_surf_height_max,
            'score' => $this->surfline_score,
            'human_relation' => $this->surfline_human_relation,
            'swell_1_height' => $this->surfline_swell_1_height,
            'swell_1_period' => $this->surfline_swell_1_period,
            'swell_1_impact' => $this->surfline_swell_1_impact,
            'swell_1_power' => $this->surfline_swell_1_power,
            'swell_1_direction' => $this->surfline_swell_1_direction,
            'swell_1_direction_min' => $this->surfline_swell_1_direction_min,
            'swell_1_optimal_score' => $this->surfline_swell_1_optimal_score,
            'swell_2_height' => $this->surfline_swell_2_height,
            'swell_2_period' => $this->surfline_swell_2_period,
            'swell_2_impact' => $this->surfline_swell_2_impact,
            'swell_2_power' => $this->surfline_swell_2_power,
            'swell_2_direction' => $this->surfline_swell_2_direction,
            'swell_2_direction_min' => $this->surfline_swell_2_direction_min,
            'swell_2_optimal_score' => $this->surfline_swell_2_optimal_score,
            'swell_3_height' => $this->surfline_swell_3_height,
            'swell_3_period' => $this->surfline_swell_3_period,
            'swell_3_impact' => $this->surfline_swell_3_impact,
            'swell_3_power' => $this->surfline_swell_3_power,
            'swell_3_direction' => $this->surfline_swell_3_direction,
            'swell_3_direction_min' => $this->surfline_swell_3_direction_min,
            'swell_3_optimal_score' => $this->surfline_swell_3_optimal_score,
        ];
    }
}
