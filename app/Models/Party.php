<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Candidate;

class Party extends Model
{
    use HasFactory;

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
