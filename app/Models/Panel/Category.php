<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Panel\Company;

class Category extends Model
{
    protected $guarded = [];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

}
