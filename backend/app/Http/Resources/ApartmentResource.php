<?php

namespace App\Http\Resources;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Apartment
 */
class ApartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $owner = $this->relationLoaded('owner') ? $this->owner : null;
        $tenant = $this->relationLoaded('tenant') ? $this->tenant : null;
        $invoice = $this->relationLoaded('currentInvoice') ? $this->currentInvoice : null;

        $meterValues = [];
        if ($this->relationLoaded('meters')) {
            foreach ($this->meters as $meter) {
                $lastReading = $meter->relationLoaded('lastReading') ? $meter->lastReading : null;
                $meterValues[$meter->type] = $lastReading?->value;
            }
        }

        return [
            'id' => $this->id,
            'number' => $this->number,
            'floor' => $this->floor,
            'area' => $this->area,
            'entranceId' => $this->entrance_id,
            'pendingCount' => $this->requests_pending_count ?? 0,
            'requestsTotal' => $this->requests_count ?? 0,
            'requestsPending' => $this->requests_pending_count ?? 0,
            'requestsResolved' => $this->requests_resolved_count ?? 0,
            'ownerName' => $owner ? trim($owner->first_name.' '.$owner->last_name) : null,
            'ownerPhone' => $owner?->profile?->phone,
            'ownerEmail' => $owner?->email,
            'tenantName' => $tenant ? trim($tenant->first_name.' '.$tenant->last_name) : null,
            'tenantPhone' => $tenant?->profile?->phone,
            'tenantEmail' => $tenant?->email,
            'waterMeter' => isset($meterValues['water']) ? (string) $meterValues['water'] : null,
            'electricityMeter' => isset($meterValues['electricity']) ? (string) $meterValues['electricity'] : null,
            'gasMeter' => isset($meterValues['gas']) ? (string) $meterValues['gas'] : null,
            'billingAmount' => $invoice?->amount,
            'dueDate' => $invoice?->due_date?->format('Y-m-d'),
            'billingStatus' => $invoice?->status,
        ];
    }
}
