<?php
// app\Models\Constituency.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Dzongkhag;
use App\Models\Candidate;
use App\Models\Votes;

class Constituency extends Model
{
    protected $fillable = [
        'name',
        'dzongkhag_id',
        'publish_result'
    ];

    public function dzongkhag()
    {
        return $this->belongsTo(Dzongkhag::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
