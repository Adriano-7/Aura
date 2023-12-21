<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

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
    
        if(Auth::check()){
            try {
                $this->authorize('show', $event);
            } catch (AuthorizationException $e) {
                abort(403, 'Não tem permissões para ver este evento.');
            }
        }
        elseif(!$event->is_public){
            abort(403, 'Não tem permissões para ver este evento.');
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

    public function joinEvent(int $id){
        if(!is_numeric($id)){
            return redirect()->back()->withErrors('Event id must be an integer');
        }
        $event = Event::find($id);
        if(!$event){
            return redirect()->back()->withErrors('Event not found');
        }
        try {
            $this->authorize('join', $event);
        } 
        catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to join this event.');
        }
        $user = Auth::user();
        $event->participants()->attach($user->id);

        $user->notifications()->where('type', 'event_invitation')->where('event_id', $id)->delete();

        return redirect()->route('event', ['id' => $id]);
    }

    public function apiJoinEvent(int $id){
        if(!is_numeric($id)){
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
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
    
    public function leaveEvent(int $id){
        if(!is_numeric($id)){
            return redirect()->back()->withErrors('Event id must be an integer');
        }
        $event = Event::find($id);
        if(!$event){
            return redirect()->back()->withErrors('Event not found');
        }
        try{
            $this->authorize('leave', $event);
        } catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to leave this event.');
        }
        $user = Auth::user();
        $event->participants()->detach($user->id);
        return redirect()->route('event', ['id' => $id]);
    }

    public function apiLeaveEvent(int $id){
        if(!is_numeric($id)){
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
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

    public function destroy(Request $request, int $id)
    {
        if (!is_numeric($id)) {
            return redirect()->back()->withErrors('Event id must be an integer');
        }
        $event = Event::find($id);
        if (!$event) {
            return redirect()->back()->withErrors('Event not found.');
        }

        try {
            $this->authorize('delete', $event);
        } catch (AuthorizationException $e) {
            return redirect()->back()->withErrors('You are not authorized to delete this event.');
        }
        $event->delete();

        return redirect()->back()->with('status', "Evento {$event->name} removido com sucesso.");
    }

    public function apiDestroy(Request $request, int $id)
    {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Event id must be an integer'], 400);
        }
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
        $request->validate([
            'email' => 'required|email',
            'event_id' => 'required|integer',
        ]);
        $event = Event::find($request->event_id);
        if (!$event) {
            return redirect()->back()->with('status', 'Evento não encontrado!');
        }
        try{
            $this->authorize('invite_user', $event);
        } catch (AuthorizationException $e) {
            return redirect()->back()->with('status', 'Não tem permissões para convidar utilizadores para este evento!');
        }

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            return redirect()->back()->with('status', 'Utilizador não encontrado!');
        } elseif ($user->id == Auth::user()->id || $user->isOrganizer($event->organization) || $user->is_admin) {
            return redirect()->back()->with('status', 'Utilizador não pode ser convidado!');
        } elseif ($user->participatesInEvent($event)) {
            return redirect()->back()->with('status', 'Utilizador já participa no evento!');
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


            if (Auth::check()) {
                $results = $eventsQuery->get()->map(function ($event) {
                    $event->isParticipating = $event->participants()->get()->contains(Auth::user());
                    
                    $event->canJoin = true;
                    try {
                        $this->authorize('join', $event);
                    } 
                    catch (AuthorizationException $e) {
                        $event->canJoin = false;
                    }

                    $event->canSee = true;
                    try{
                        $this->authorize('show', $event);
                    } catch (AuthorizationException $e) {
                        $event->canSee = false;
                    }

                    return $event;
                });
            } else {
                $results = $eventsQuery->where('is_public', true)->get()->map(function ($event) {
                    $event->isParticipating = false;
                    $event->canJoin = false;
                    return $event;
                });
            }
        
            return response()->json($results);
}}
