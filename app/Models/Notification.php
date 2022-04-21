<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignedto',
        'markmsg',
        'notifno',
        'complaint_id',
        'from',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
