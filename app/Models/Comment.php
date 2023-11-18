<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $author_id
 * @property string $text
 * @property string $date
 * @property int $vote_balance
 * @property int $event_id
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereVoteBalance($value)
 * @mixin \Eloquent
 */
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
        'event_id'
    ];

    /**
     * Get all comments for a given event.
     */
    static public function event_comments(int $event_id): Collection {
        return Comment::where('event_id', $event_id)->get();
    }
}
