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
        'location',
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

    public function isPublic(){
        return $this->is_public;
    }
}
