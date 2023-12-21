<?php

namespace App\Models;

use App\Models\Poll;
use App\Models\PollVote;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='poll_option';
    protected $fillable = [
        'id',
        'poll_id',
        'text',
    ];

    public function poll() {
        return $this->belongsTo(Poll::class);
    }

    public function votes() {
        return $this->hasMany(PollVote::class);
    }

    public function hasUserVoted(int $userId) {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getPercentage() {
        $voteCount = optional($this->votes())->count() ?? 0;
        $totalVotes = $this->poll->votes()->count();
        $percentage = $totalVotes > 0 ? ($voteCount / $totalVotes) * 100 : 0;
        return round($percentage, 2);
    }
}