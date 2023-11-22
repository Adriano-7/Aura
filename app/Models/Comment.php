<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='comments';

    // not all are fillable (change later)
    protected $fillable = [
        'id',
        'author_id',
        'text',
        'date',
        'vote_balance',
        'event_id',
        'file_id'
    ];

    /**
     * Get all comments for a given event, sorted by date (newest first)
     */
    static public function event_comments(int $event_id): Collection {
        return Comment::where('event_id', $event_id)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

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

    DROP TABLE IF EXISTS files CASCADE;
    CREATE TABLE files (
        id SERIAL PRIMARY KEY,
        comment_id INTEGER NOT NULL REFERENCES comments (id) ON DELETE CASCADE,
        file_name TEXT NOT NULL
    );


    */ 

    public function file() {
        return $this->hasOne(File::class, 'id', 'file_id');
    }
}
