<?php

// app\Models\Dzongkhag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dzongkhag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function constituencies()
    {
        return $this->hasMany(Constituency::class);
    }
}
