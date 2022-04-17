<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'NPSDNumber',
        'formType',
        'receivedBy',
        'assignedTo',
        'violation',
        'similar',
        'counterCharge',
        'counterChargeDetails',
        'relatedComplaint',
        'relatedDetails',
        'placeofCommission'
    ];
    
    public function party()
    {
        return $this->hasMany(Party::class);
    }

    public function violatedlaw()
    {
        return $this->hasMany(violatedlaw::class);
    }

    public function attachment()
    {
        return $this->hasMany(Attachment::class);
    }

    public function prosecutor()
    {
        return $this->hasOne(Prosecutor::class);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
