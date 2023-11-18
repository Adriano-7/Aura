<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model{
    use HasFactory;

    protected $table = 'administrators';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function notificationRegReqOrgs(){
        return $this->hasMany(NotificationRegReqOrg::class, 'receiver_id');
    }
}
