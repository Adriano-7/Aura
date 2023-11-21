<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'id',
        'name',
        'description',
        'photo',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    public function organizers(){
        return $this->belongsToMany(User::class, 'organizers', 'organization_id', 'user_id');
    }

    public function invitedUsers(){
        return $this->belongsToMany(User::class, 'notifications', 'organization_id', 'receiver_id')->where('type', 'organization_invitation');
    }
}