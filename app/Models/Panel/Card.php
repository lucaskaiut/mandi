<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];

    public function bandeiras()
    {
        return $this->hasMany('App\Models\Panel\Bandeira');
    }
}
