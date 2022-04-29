<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'createdBy'
    ];

    public function office()
    {
        return $this->belongsTo(Complaint::class);
    }
}
