<?php
// app\Models\Constituency.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Constituency;
use App\Models\Party;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'party_id',
        'constituency_id',
        'profile_image'
    ];

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
