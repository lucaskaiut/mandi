<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;

class CartaoMovimento extends Model
{
    protected $guarded = [];

    public function search(Array $data)
    {
        if (!isset($data['paginate'])) {
            $data['paginate'] = 15;
        }
        return $this->where(function ($query) use ($data) {
            if (isset($data['tipo'])) {
                $query->where('tipo', $data['tipo']);
            }
            if (isset($data['entrada'])) {
                $query->where('entrada', $data['entrada']);
            }
            if (isset($data['liquidado'])) {
                if ($data['liquidado'] != 2) {
                    $query->where('liquidado', $data['liquidado']);
                }
            }
        })->orderBy($data['orderBy'])
            ->paginate($data['paginate']);

    }

}
