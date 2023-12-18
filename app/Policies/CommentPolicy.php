<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\Response;


class CommentPolicy
{
    public function delete(User $user, Comment $comment){
        if ($user->id === $comment->user_id || $user->is_admin){
            return Response::allow();
        }
        return Response::deny('Não pode apagar este comentário.');
    }

    public function store(User $user, Event $event){
        if ($user->participatesInEvent($event)){
            return Response::allow();
        }
        return Response::deny('Apenas participantes podem comentar no evento.');
    }

    public function update(User $user, Comment $comment){
        if ($user->id === $comment->user_id){
            return Response::allow();
        }
        return Response::deny('Não pode editar este comentário.');
    }
}
