<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigatedCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days',
        'receivedby',
        'complaint_id',
        'assignedto',
        // 'is_read',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
