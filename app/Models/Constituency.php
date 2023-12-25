<?php
// app\Models\Constituency.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dzongkhag;
use App\Models\Candidate;


class Constituency extends Model
{
    protected $fillable = [
        'name',
        'dzongkhag_id'
    ];

    public function dzongkhag()
    {
        return $this->belongsTo(Dzongkhag::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
