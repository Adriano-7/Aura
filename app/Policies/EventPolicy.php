<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;

class EventPolicy{
    public function join(User $user, Event $event){
        if ($user->is_admin){
            return Response::deny('Administrador não pode participar num evento.');
        }
        if ($event->participants()->get()->contains($user)){
            return Response::deny('Já participa neste evento.');
        }
        return Response::allow();
    }

    public function leave(User $user, Event $event){
        if (!$event->participants()->get()->contains($user)){
            return Response::deny('Não participa neste evento.');
        }
        return Response::allow();
    }
    public function delete(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        if($user->is_admin || $org_users->contains($user)){
            return Response::allow();
        }
        return Response::deny('Apenas administradores e membros da organização podem apagar eventos.');
    }

    public function update(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        if($user->is_admin || $org_users->contains($user)){
            return Response::allow();
        }
        return Response::deny('Apenas administradores e membros da organização podem editar eventos.');
    }


    public function invite_user(User $user, Event $event){
        if(!$user->is_admin){
            return Response::allow();
        }
        return Response::deny('Administrador não pode convidar utilizadores.');
    }

    public function viewEditForm(User $user, Event $event){
        $organisations = Organization::findOrFail($event->organization_id);
        $org_users = $organisations->organizers()->get();

        if($user->is_admin || $org_users->contains($user)){
            return Response::allow();
        }
        return Response::deny('Apenas administradores e membros da organização podem editar eventos.');
    }
}
