<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'fornecedor_id', 'id');
    }

    public function search(Array $dataForm)
    {
        if (isset($dataForm['paginate'])) {
            $paginate = ($dataForm['paginate']);
        } else {
            $paginate = 15;
        }

        return $this->where(function ($query) use ($dataForm) {
            if ($dataForm['ativo'] != 2) {
                $query->where('ativo', $dataForm['ativo']);
            }
            if (isset($dataForm['id'])) {
                $query->where('id', $dataForm['id']);
            }
            if (isset($dataForm['fornecedor'])) {
                $query->where('fornecedor', $dataForm['fornecedor']);
            } else {
                $query->where('fornecedor', 'a');
            }
            if (isset($dataForm['razao_social'])) {
                $query->where('razao_social', 'like', '%' . $dataForm['razao_social'] . '%');
            }
            if (isset($dataForm['orderBy'])) {
                $query->orderBy($dataForm['orderBy']);
            } else {
                $query->orderBy('id');
            }

        })->paginate($paginate);

    }
}
