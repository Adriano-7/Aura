<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps  = false;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'is_admin',
        'name',
        'username',
        'email',
        'password',
        'photo',
        'background_color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function participatesInEvent(Event $event) {
        return $event->participants()->get()->contains($this);
    }

    public function organizations(){
        return $this->belongsToMany('App\Models\Organization', 'organizers', 'user_id', 'organization_id');
    }

    public function isOrganizer(Organization $organization){
        return $this->organizations->contains($organization);
    }

    public function notifications(): HasMany{
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function eventsWhichParticipates(){
        $partEvents = Event::whereHas('participants', function ($query) { $query->where('user_id', $this->id);})->get();
        return $partEvents;
    }
}
