<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'age',
        'address',
        'belongsTo'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
