<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'reports_comment';

    protected $fillable = [
        'id',
        'comment_id',
        'resolved',
        'date',
        'reason'
    ];

    public function comment() {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function getReasonText() {
        switch ($this->reason) {
            case 'inappropriate_content':
                return 'Conteúdo inadequado ou não apropriado';
            case 'violence_threats':
                return 'Ameaças ou incitação à violência';
            case 'incorrect_information':
                return 'Informações incorretas ou enganosas';
            case 'harassment_bullying':
                return 'Assédio ou bullying';
            case 'commercial_spam':
                return 'Conteúdo comercial ou spam';
            default:
                throw new \Exception("Invalid report reason: {$this->reason}");
        }
    }
}
