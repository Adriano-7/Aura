<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoteComment;
use App\Models\Comment;
use Illuminate\Auth\Access\Response;


class VoteCommentPolicy{
    public function addVote(User $user, Comment $comment){
        if ($user->is_admin){
            return Response::deny('Administrador não pode votar.');
        }
        if ($comment->author == $user){
            return Response::deny('Não pode votar no seu próprio comentário.');
        }
        if($comment->userVote($user->id) != 0){
            return Response::deny('Não pode votar duas vezes no mesmo comentário.');
        }
        return Response::allow();
    }

    public function deleteVote(User $user, Comment $comment){
        if($comment->userVote($user->id) == 0){
            return Response::deny('Não pode apagar um voto que não existe.');
        }
        return Response::allow();
    }
}
