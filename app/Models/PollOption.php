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

}