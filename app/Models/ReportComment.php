<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;

    protected $table = 'reports_comment';
    public $timestamps = false;


    // check later (not all fields are fillable)
    protected $fillable = [
        'id',
        'comment_id',
        'resolved',
        'date',
        'reason_id'
    ];

    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    public function reason() {
        return $this->belongsTo(ReasonReportComment::class);
    }
}
