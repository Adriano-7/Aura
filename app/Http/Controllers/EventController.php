<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Event;
use App\Models\Notification;
use App\Models\User;


class EventController extends Controller{
    public function show($id): View {
        return view('pages.event', [
            'user' => Auth::user(),
            'event' => Event::find($id)
        ]);
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('home')->with('status', "Evento {$event->name} removido com sucesso.");
    }

    public function leaveEvent($id) {
        $event = Event::find($id);
        $this->authorize('leave', $event);
        $user = Auth::user();
        $event->participants()->detach($user->id);
        return response()->json(['status' => 'success']);
    }

    public function joinEvent($id) {
        $event = Event::find($id);
        $this->authorize('leave', $event);
        $user = Auth::user();
        $event->participants()->attach($user->id);
        return response()->json(['status' => 'success']);
    }

    public function search(Request $request){
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
                $queryBuilder->where('name', 'ILIKE', "%$query%");
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
            })
            ->orderBy('start_date', 'asc');

        $results = $eventsQuery->get();

        return response()->json($results);
    }

    public function ApiDelete(int $id) {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found.'
            ], 404);
        }

        $this->authorize('delete', $event);
        $event->delete();

        return response()->json([
            'message' => 'Event deleted.'
        ], 200);
    }

    public function inviteUser(Request $request){
        $event = Event::findOrFail($request->event_id);
        $this->authorize('invite_user', $event);

        $user = User::where('email', $request->email)->first();

        if($user == null){
            return redirect()->back()->with('status', 'Utilizador não encontrado!');
        }
        elseif($user->id == Auth::user()->id || $user->isOrganizer($event->organization) || $user->isAdmin()){
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
}
