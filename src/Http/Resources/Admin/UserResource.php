<?php

namespace Modules\ModuleRelease2\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "status" => $this->status,
            "roles" => $this->role,
            "province" => $this->province_name,
            "city" => $this->city_name,
            "district" => $this->district_name,
            "village" => $this->village_name,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
