<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolatedLaw extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'details',
        'docketNo'
        
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
