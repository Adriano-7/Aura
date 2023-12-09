<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteComment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'vote_comments';

    protected $fillable = [
        'id',
        'comment_id',
        'user_id',
        'is_up'
    ];

    protected $casts = [
        'is_up' => 'boolean'
    ];

    static public function voteValue($comment_id, $user_id) : int{
        $vote = VoteComment::where('user_id', $user_id)->where('comment_id', $comment_id)->first();
        if ($vote) {
            return $vote->is_up ? 1 : -1;
        }
        return 0;
    }

    static public function addVote($comment_id, $user_id, $isUp) {
        $vote = new VoteComment();
        $vote->comment_id = $comment_id;
        $vote->user_id = $user_id;
        $vote->is_up = $isUp;
        $vote->save();
    }

    static public function deleteVote($comment_id, $user_id) {
        $vote = VoteComment::where('user_id', $user_id)->where('comment_id', $comment_id)->first();
        $vote->delete();
    }
}
