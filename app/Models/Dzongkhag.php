<?php

// app\Models\Dzongkhag.php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\User;

class Dzongkhag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function constituencies()
    {
        return $this->hasMany(Constituency::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
