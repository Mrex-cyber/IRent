<?php

namespace App\Http\Resources;

use App\Models\Entrance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Entrance
 */
class EntranceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'buildingId' => $this->building_id,
            'apartmentCount' => $this->apartments_count ?? 0,
        ];
    }
}
