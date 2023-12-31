<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VoteComment;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='comments';

    // not all are fillable (change later)
    protected $fillable = [
        'id',
        'user_id',
        'text',
        'date',
        'vote_balance',
        'event_id',
        'file_id'
    ];


    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get all comments for a given event, sorted by date (newest first)
     */
    static public function event_comments(int $event_id): Collection {
        return Comment::where('event_id', $event_id)->orderBy('date', 'desc')->get();
    }

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function file() {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function userVote($user_id) {
        return VoteComment::voteValue($this->id, $user_id);
    }

}
