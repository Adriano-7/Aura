<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'id',
        'name',
        'description',
        'photo',
        'address',
        'venue',
        'city',
        'start_date',
        'end_date',
        'is_public',
        'organization_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_public' => 'boolean'
    ];

    public function tags(){
        return $this->belongsToMany('App\Models\Tag', 'tag_event', 'event_id', 'tag_id');
    }
    
    public function participants(){
        return $this->belongsToMany(User::class, 'participants', 'event_id', 'user_id');
    }
}
