<?php

namespace App\Http\Requests\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');

        if (!$event instanceof Event) {
            return false;
        }

        $user = $this->user();

        if (!$user) {
            return false;
        }

        return $user->hasRole(['admin']) || $event->organizer?->is($user);
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
    public function validatedForUpdate(): array
    {
        $validated = $this->validated();

        $validated['tags'] = Event::normalizeTags($validated['tags'] ?? null);

        return $validated;
    }
}

