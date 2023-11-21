<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonReportEvent extends Model
{
    use HasFactory;

    protected $table = 'report_reasons_event';

    // check later (not all fields are fillable)
    protected $fillable = [
        'id',
        'text'
    ];
}
