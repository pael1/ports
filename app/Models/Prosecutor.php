<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosecutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ext',
        'firstname',
        'lastname',
        'middlename',
        'reviewer',
        'schedule',
        'court',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
