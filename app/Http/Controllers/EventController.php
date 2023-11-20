<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Event;


class EventController extends Controller
{
    public function show($id): View
    {
        return view('pages.event', [
            'user' => Auth::user(),
            'event' => Event::find($id)
        ]);
    }

    
    public function joinEvent($id){
        $event = Event::find($id);
        $this->authorize('join', $event);
        $event->participants()->attach(Auth::user()->id);
        $event->save();

        return redirect()->route('notifications')->
            with('status', "Entrou com sucesso no evento {$event->name}, {$event->venue} em {$event->start_date->format('j F, Y')}.");    
    }

    public function search(Request $request){
        $request->validate([
            'query' => 'nullable|string',
            'tags' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = $request->input('query');
        $tags = $request->input('tags');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $eventsQuery = Event::with('tags')
            ->when($query, function (Builder $queryBuilder) use ($query) {
                $queryBuilder->where('name', 'like', "%$query%");
            })
            ->when($tags, function (Builder $queryBuilder) use ($tags) {
                $queryBuilder->whereHas('tags', function (Builder $tagQuery) use ($tags) {
                    $tagQuery->whereIn('name', $tags);
                });
            })
            ->when($startDate, function (Builder $queryBuilder) use ($startDate) {
                $queryBuilder->where('start_date', '>=', $startDate);
            })
            ->when($endDate, function (Builder $queryBuilder) use ($endDate) {
                $queryBuilder->where('end_date', '<=', $endDate);
            })
            ->orderBy('start_date', 'asc');

        $results = $eventsQuery->get();

        return response()->json($results);
    }
}