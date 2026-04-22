<?php

namespace App\Services;

use App\Models\ManagementSetting;
use Illuminate\Support\Collection;

class SettingsService
{
    public function getGrouped(): array
    {
        $settings = ManagementSetting::all();

        return $settings->groupBy('group')->map(function (Collection $group) {
            return $group->map(fn (ManagementSetting $s) => [
                'key' => $s->key,
                'value' => $this->castValue($s->value, $s->type),
                'type' => $s->type,
                'label' => $s->label,
            ])->values();
        })->all();
    }

    public function updateMany(array $items): void
    {
        foreach ($items as $key => $value) {
            ManagementSetting::where('key', $key)->update(['value' => (string) $value]);
        }
    }

    private function castValue(string $raw, string $type): mixed
    {
        return match ($type) {
            'boolean' => $raw === 'true',
            'number' => is_numeric($raw) ? (float) $raw : $raw,
            default => $raw,
        };
    }
}
