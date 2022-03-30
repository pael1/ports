<?php

namespace App\Models;

// use App\Models\Complaint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'filename',
        'path'
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
