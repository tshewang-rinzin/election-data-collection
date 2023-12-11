<?php
// app\Models\Constituency.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
