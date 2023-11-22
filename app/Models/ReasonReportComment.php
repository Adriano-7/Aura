<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonReportComment extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $table = 'report_reasons_comment';


    // check later (not all fields are fillable)
    protected $fillable = [
        'id',
        'text'
    ];
}
