<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'nullable|in:direct,group',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'integer|exists:users,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('type', 'direct');
            if ($type === 'group') {
                $participantIds = array_values(array_unique(array_merge(
                    [$this->user()->id],
                    $this->input('participant_ids', [])
                )));
                if (count($participantIds) < 2) {
                    $validator->errors()->add(
                        'participant_ids',
                        'Group must have at least one other participant.'
                    );
                }
            }
        });
    }
}
