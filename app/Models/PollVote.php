<?php

namespace App\Models;

use App\Models\Poll;
use App\Models\User;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='poll_vote';
    protected $fillable = [
        'id',
        'poll_option_id',
        'user_id',
    ];

    public function pollOption()
    {
        return $this->belongsTo(PollOption::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    static public function addVote($option_id, $user_id) {
        $vote = new PollVote;
        $vote->poll_option_id = $option_id;
        $vote->user_id = $user_id;
        
        $vote->save();
        error_log("Vote added");
    }

    
   

    

}