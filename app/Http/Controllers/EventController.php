<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Access\AuthorizationException;

use App\Models\Event;
use App\Models\Notification;
use App\Models\User;


class EventController extends Controller{
    public function show($id): View
    {
        if (!is_numeric($id)) {
            abort(404, 'Evento não encontrado.');
        }
        $event = Event::find($id);
        if(!$event){
            abort(404, 'Evento não encontrado.');
        }
    
        if (Auth::check()) {
            $comments = $event->comments()
                ->with('author')
                ->orderByRaw('user_id = ? DESC', [Auth::user()->id])
                ->orderBy('id', 'DESC')
                ->get();        
        } 
        else {
            $comments = $event->comments()->get();
        }

        return view('pages.event', [
            'user' => Auth::user(),
            'event' => $event,
            'comments' => $comments,
        ]);
    }

    public function joinEvent($id)
    {
        $event = Event::find($id);
        $this->authorize('join', $event);
        $user = Auth::user();
        $event->participants()->attach($user->id);

        $user->notifications()->where('type', 'event_invitation')->where('event_id', $id)->delete();

        return redirect()->route('event', ['id' => $id]);
    }

    public function apiJoinEvent($id){
        $event = Event::find($id);
        if(!$event){
            return response()->json(['message' => 'Event not found.'], 404);
        }

        try {
            $this->authorize('join', $event);
        } 
        catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to join this event.'], 403);
        }

        $user = Auth::user();
        $event->participants()->attach($user->id);
        $user->notifications()->where('type', 'event_invitation')->where('event_id', $id)->delete();

        return response()->json(['message' => 'User joined event.'], 200);
    }
    
    public function leaveEvent($id)
    {
        $event = Event::find($id);
        $this->authorize('leave', $event);
        $user = Auth::user();
        $event->participants()->detach($user->id);
        return redirect()->route('event', ['id' => $id]);
    }

    public function apiLeaveEvent($id){
        $event = Event::find($id);
        if(!$event){
            return response()->json(['message' => 'Event not found.'], 404);
        }

        try {
            $this->authorize('leave', $event);
        } 
        catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to leave this event.'], 403);
        }

        $user = Auth::user();
        $event->participants()->detach($user->id);

        return response()->json(['message' => 'User left event.'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->back()->with('status', "Evento {$event->name} removido com sucesso.");
    }

    public function apiDestroy(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        try {
            $this->authorize('delete', $event);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'User not authorized to delete this event.'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted.'], 200);
    }

    public function inviteUser(Request $request)
    {
        $event = Event::findOrFail($request->event_id);
        $this->authorize('invite_user', $event);

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            return redirect()->back()->with('status', 'Utilizador não encontrado!');
        } elseif ($user->id == Auth::user()->id || $user->isOrganizer($event->organization) || $user->is_admin) {
            return redirect()->back()->with('status', 'Utilizador não pode ser convidado!');
        }

        $notification = new Notification();
        $notification->receiver_id = $user->id;
        $notification->type = 'event_invitation';
        $notification->user_emitter_id = Auth::user()->id;
        $notification->event_id = $event->id;
        $notification->date = now();
        $notification->save();

        return redirect()->back()->with('status', 'Utilizador convidado com sucesso!');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string',
            'tags' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = $request->input('query');
        $tags = $request->input('tags');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $eventsQuery = Event::with('tags')
            ->when($query, function (Builder $queryBuilder) use ($query) {
                $queryBuilder->whereRaw("tsvectors @@ phraseto_tsquery('portuguese', ?)", [$query])
                    ->orderByRaw("ts_rank(tsvectors, phraseto_tsquery('portuguese', ?)) DESC", [$query]);
            })
            ->when($tags, function (Builder $queryBuilder) use ($tags) {
                $queryBuilder->whereHas('tags', function (Builder $tagQuery) use ($tags) {
                    $tagQuery->where('id', $tags);
                });
            })
            ->when($startDate, function (Builder $queryBuilder) use ($startDate) {
                $queryBuilder->where('start_date', '>=', $startDate);
            })
            ->when($endDate, function (Builder $queryBuilder) use ($endDate) {
                $queryBuilder->where('end_date', '<=', $endDate);
            });

        $results = $eventsQuery->get()->map(function ($event) {
            $event->isParticipating = $event->participants()->get()->contains(Auth::user());
            return $event;
        });

        return response()->json($results);
    }}
