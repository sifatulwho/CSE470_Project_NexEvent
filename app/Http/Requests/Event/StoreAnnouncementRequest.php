<?php

namespace App\Http\Requests\Event;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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

        return $event->organizer?->is($user) || $user->hasRole(['admin']);
    }

    /**
     * @return array<string, array<int|string, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}

