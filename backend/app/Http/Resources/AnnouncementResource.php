<?php

namespace App\Http\Resources;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Announcement
 */
class AnnouncementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = $this->relationLoaded('author') ? $this->author : $this->author()->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'category' => ucfirst($this->type),
            'status' => ucfirst($this->status),
            'publishedAt' => $this->status === 'published' ? $this->updated_at?->format('Y-m-d') : null,
            'scheduledAt' => $this->scheduled_for?->format('Y-m-d'),
            'createdAt' => $this->created_at->format('Y-m-d'),
            'author' => [
                'id' => $author?->id,
                'name' => $author ? trim($author->first_name.' '.$author->last_name) : 'Unknown',
            ],
        ];
    }
}
