<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'user_id', 
        'title', 
        'content', 
        'type', 
        'status', 
        'scheduled_for'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeVisibleForResidents(Builder $query): void
    {
        $query->whereIn('status', ['published', 'scheduled']);
    }

    public function scopeByType(Builder $query, string $type): void
    {
        if ($type !== 'all') {
            $query->where('type', $type);
        }
    }
}
