<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Panel\Card;

class Bandeira extends Model
{
    protected $guarded = [];

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id', 'id');
    }
}
