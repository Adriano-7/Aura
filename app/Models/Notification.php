<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'date',
        'seen',
        'receiver_id',
        'type',
        'organization_id',
        'changed_field',
        'user_emitter_id',
        'event_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'seen' => 'boolean'

    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function event(){
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function userEmitter(){
        return $this->belongsTo(User::class, 'user_emitter_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /*
            'type' - 'event_invitation', 'event_edit', 'organization_invitation', 'organization_registration_request', 'organization_registration_response'
    */
    public function getSenderName()
    {
        switch ($this->type) {
            case 'event_invitation':
                return $this->userEmitter->name;
            case 'event_edit':
                return $this->event->name;
            case 'organization_invitation':
                return $this->userEmitter->name;
            case 'organization_registration_request':
                return $this->userEmitter->name;
            case 'organization_registration_response':
                return $this->organization->name;
            default:
                throw new \Exception("Invalid notification type: {$this->type}");
        }
    }

    public function getNiceDate(){
        $now = now();
        $timeUnits = [
            'min' => $now->diffInMinutes($this->date),
            'hora' => $now->diffInHours($this->date),
            'dia.' => $now->diffInDays($this->date),
            'semana' => $now->diffInWeeks($this->date),
            'mese' => $now->diffInMonths($this->date),
            'ano' => $now->diffInYears($this->date),
        ];
    
        $limits = [60, 24, 7, 4, 12];
        $i = 0;
        foreach ($timeUnits as $unit => $value) {
            if ($i < count($limits) && $value < $limits[$i]) {
                $sufix = $value > 1 ? 's' : '';
                return $value . ' ' . $unit . $sufix  . ' atrás';
            }
            $i++;
        }
    
        return end($timeUnits) . ' anos atrás';
    }

    public function getContent(){
        switch ($this->type) {
            case 'event_invitation':
                return "{$this->userEmitter->name} convidou-te a participar no evento {$this->event->name}.";
            case "event_edit":
                return "O evento {$this->event->name} foi alterado.";
            case "organization_invitation":
                return "{$this->userEmitter->name} convidou-te a participar na organização {$this->organization->name}.";
            case "organization_registration_request":
                return "{$this->userEmitter->name} pediu o registo da organização {$this->organization->name}.";
            case "organization_registration_response":
                return "A organização {$this->organization->name} foi aprovada.";
            default:
                throw new \Exception("Invalid notification type: {$this->type}");
        }
    }

    public function getImage(){
        switch ($this->type) {
            case 'event_invitation':
            case 'organization_invitation':
            case 'organization_registration_request':
                return "storage/profile/{$this->userEmitter->photo}";

            case 'event_edit':
                return "storage/eventos/{$this->event->photo}";

            case 'organization_registration_response':
                return "storage/organizations/{$this->organization->photo}";

            default:
                throw new \Exception("Invalid notification type: {$this->type}");
        }
    }

    public function getLink(){
        switch($this->type){
            case 'event_invitation':
            case 'event_edit':
                    return route('event', ['id' => $this->event->id]);
            case 'organization_invitation':
            case 'organization_registration_request':
            case 'organization_registration_response':
                return route('organization', ['id' => $this->organization->id]);
            default:
                throw new \Exception("Invalid notification type: {$this->type}");
        }
    }
}
