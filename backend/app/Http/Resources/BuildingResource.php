<?php

namespace App\Http\Resources;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Building
 */
class BuildingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->address.', '.$this->city,
            'address' => $this->address,
            'city' => $this->city,
            'entrancesCount' => $this->entrances_count ?? 0,
        ];
    }
}
