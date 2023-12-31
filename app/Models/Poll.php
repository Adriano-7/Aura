<?php

namespace App\Models;

use App\Models\Event;
use App\Models\PollOption;
use App\Models\PollVote;




use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'polls';
    protected $fillable = [
        'id',
        'event_id',
        'question',
        'date'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasManyThrough(PollVote::class, PollOption::class);
    }

    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function optionUserVoted($userId)
    {
        error_log("Poll option user voted");
        $vote = $this->votes()->where('user_id', $userId)->first();

        return $vote;


    }
}