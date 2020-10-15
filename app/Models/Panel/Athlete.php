<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Panel\Category;

class Athlete extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function search(Array $data, $totalPage)
    {

        if ($data['athlete_category'] != 'Todos')
            return $athletes = $this->where('athlete_category', $data['athlete_category'])
                ->where('active', 1)
                ->orderBy('matricula')
                ->paginate($totalPage);


        return $athletes = $this->where('active', 1)
            ->orderBy('matricula')
            ->paginate($totalPage);

    }

    public function pendingAthlete()
    {
        return $athletes = $this->where('active', 0)->where('deleted', 0)->get();
    }

}
