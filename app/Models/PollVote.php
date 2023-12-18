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

    protected $table='poll_option';
    protected $fillable = [
        'id',
        'poll_option_id',
        'user_id',
    ];

   

}