<?php

namespace App\Http\Requests\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin', 'organizer']) ?? false;
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'venue' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(Event::CATEGORY_OPTIONS)],
            'tags' => ['nullable', 'string'],
            'visibility' => ['required', Rule::in(Event::VISIBILITY_OPTIONS)],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedForStorage(): array
    {
        $validated = $this->validated();

        $validated['tags'] = Event::normalizeTags($validated['tags'] ?? null);

        return $validated;
    }
}

