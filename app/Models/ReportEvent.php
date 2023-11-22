<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportEvent extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'reports_event';


    // check later (not all fields are fillable)
    protected $fillable = [
        'id',
        'event_id',
        'resolved',
        'date',
        'reason_id'
    ];

    public function event() {
        return $this->belongsTo(Event::class, 'reason_id');
    }

    public function reason() {
        return $this->belongsTo(ReasonReportEvent::class, 'reason_id');
    }
}
