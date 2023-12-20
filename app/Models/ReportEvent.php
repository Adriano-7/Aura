<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportEvent extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'reports_event';

    protected $fillable = [
        'id',
        'event_id',
        'resolved',
        'date',
        'reason'
    ];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function getReasonText() {
        switch ($this->reason) {
            case 'suspect_fraud':
                return 'Suspeita de fraude ou golpe';
            case 'inappropriate_content':
                return 'Conteúdo inadequado ou ofensivo';
            case 'incorrect_information':
                return 'Informações incorretas sobre o evento';
            default:
                throw new \Exception("Invalid report reason: {$this->reason}");
        }
    }
}
