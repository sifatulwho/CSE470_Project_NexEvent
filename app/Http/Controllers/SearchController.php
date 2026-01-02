<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for events.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category');
        
        $events = Event::query()
            ->where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('location', 'like', '%' . $query . '%');
            });

        if ($category) {
            $events->where('category', $category);
        }

        $events = $events->orderBy('start_date', 'asc')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events', 'query', 'category'));
    }
}
