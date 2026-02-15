<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'employee_id' => $this->employee_id,
            'fullname' => $this->fullname,
            'work_date' => $this->work_date,
            'clock_in_time' => $this->clock_in_time,
            'clock_out_time' => $this->clock_out_time,
        ];
    }
}
