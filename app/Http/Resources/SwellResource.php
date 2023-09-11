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
            'surf_height_min' => $this->surfline_surf_height_min,
            'surf_height_max' => $this->surfline_surf_height_max,
            'score' => $this->surfline_score,
            'human_relation' => $this->surfline_human_relation,
            'swell_1' => [
                'height' => $this->surfline_swell_1_height,
                'period' => $this->surfline_swell_1_period,
                'impact' => $this->surfline_swell_1_impact,
                'power' => $this->surfline_swell_1_power,
                'direction' => $this->surfline_swell_1_direction,
                'direction_min' => $this->surfline_swell_1_direction_min,
                'optimal_score' => $this->surfline_swell_1_optimal_score,
            ],
            'swell_2' => [
                'height' => $this->surfline_swell_2_height,
                'period' => $this->surfline_swell_2_period,
                'impact' => $this->surfline_swell_2_impact,
                'power' => $this->surfline_swell_2_power,
                'direction' => $this->surfline_swell_2_direction,
                'direction_min' => $this->surfline_swell_2_direction_min,
                'optimal_score' => $this->surfline_swell_2_optimal_score,
            ],
            'swell_3' => [
                'height' => $this->surfline_swell_3_height,
                'period' => $this->surfline_swell_3_period,
                'impact' => $this->surfline_swell_3_impact,
                'power' => $this->surfline_swell_3_power,
                'direction' => $this->surfline_swell_3_direction,
                'direction_min' => $this->surfline_swell_3_direction_min,
                'optimal_score' => $this->surfline_swell_3_optimal_score,
            ],
        ];
    }
}
