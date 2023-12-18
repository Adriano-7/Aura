<?php

namespace App\Models;

use App\Models\Event;
use App\Models\PollOption;



use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='polls';
    protected $fillable = [
        'id',
        'event_id',
        'question',
        'date'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function options() {
        return $this->hasMany(PollOption::class);
    }


}