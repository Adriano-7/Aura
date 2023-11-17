<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model{
    use HasFactory;

    protected $table = 'clients';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function participants(){
        return $this->hasMany(Participant::class, 'user_id');
    }

    public function organizers(){
        return $this->hasMany(Organizer::class, 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'author_id');
    }

    public function notificationInvEvents(){
        return $this->hasMany(NotificationInvEvent::class, 'receiver_id');
    }

    public function notificationInvOrgs(){
        return $this->hasMany(NotificationInvOrg::class, 'receiver_id');
    }

    public function notificationRegReqOrgs(){
        return $this->hasMany(NotificationRegReqOrg::class, 'receiver_id');
    }
}
