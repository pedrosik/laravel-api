<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['employees'] = EmployeeResource::collection($this->whenLoaded('employees'));

        return $data;
    }
}
