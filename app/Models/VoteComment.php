<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
    DROP TABLE IF EXISTS comments CASCADE;
    CREATE TABLE comments (
        id SERIAL PRIMARY KEY,
        author_id INTEGER NOT NULL REFERENCES clients (id) ON DELETE CASCADE,
        text TEXT NOT NULL,
        date TIMESTAMP NOT NULL DEFAULT current_timestamp,
        vote_balance INT NOT NULL DEFAULT 0,
        event_id INTEGER NOT NULL REFERENCES events (id) ON DELETE CASCADE,
        file_id INTEGER REFERENCES files (id) ON DELETE CASCADE
    );


    DROP TABLE IF EXISTS vote_comments CASCADE;
    CREATE TABLE vote_comments (
        comment_id INTEGER REFERENCES comments (id) ON DELETE CASCADE,
        user_id INTEGER REFERENCES clients (id) ON DELETE CASCADE,
        is_up BOOLEAN NOT NULL,
        PRIMARY KEY (comment_id, user_id)
    );
*/

class VoteComment extends Model
{
    use HasFactory;

    protected $table = 'vote_comments';

    protected $fillable = [
        'comment_id',
        'user_id',
        'is_up'
    ];


    protected $casts = [
        'is_up' => 'boolean'
    ];

    static public function voteValue($user_id, $comment_id) : int{
        $vote = self::where('user_id', $user_id)->where('comment_id', $comment_id)->first();

        if ($vote) {
            return $vote->is_up ? 1 : -1;
        }

        return 0;
    }
}
