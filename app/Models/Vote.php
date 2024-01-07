<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Constituency;
use App\Models\Party;
use Spatie\Activitylog\Traits\LogsActivity;



class Vote extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['constituency_id', 'party_id', 'evm', 'postal_ballot'];

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
