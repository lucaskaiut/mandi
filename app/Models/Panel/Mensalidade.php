<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    protected $guarded = [];

    public function search(Array $data, $paginate)
    {
        return $this->where(function ($query) use ($data) {
        //$mensalidades = $this->where(function ($query) use ($data) {
            if(isset($data['id'])){
                $query->where('id', $data['id']);
            }
            if(isset($data['athlete_id'])){
                $query->where('athlete_id', $data['athlete_id']);
            }
            if(isset($data['atleta'])){
                $query->where('atleta', 'like', '%'.$data['atleta'].'%');
            }
            if(isset($data['mes'])){
                $query->where('ref_mes', $data['mes']);
            }
            if (isset($data['lancamento_inicio']) && isset($data['lancamento_fim'])) {
                if (isset($data['pagamento_inicio']) && isset($data['pagamento_fim'])) {
                    $query
                        ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                        ->whereBetween('pagamento', [$data['pagamento_inicio'], $data['pagamento_fim']]);

                } else {
                    if (!isset($data['pagamento_inicio']) && !isset($data['pagamento_fim'])) {
                        $query
                            ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59']);
                    } else {
                        if (isset($data['pagamento_inicio'])) {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('pagamento', '>=', $data['pagamento_inicio']);
                        } else {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('pagamento', '<=', $data['pagamento_fim']);
                        }
                    }
                }
            } else {

                if (!isset($data['lancamento_inicio']) && !isset($data['lancamento_fim'])) {
                    if (isset($data['pagamento_inicio']) && isset($data['pagamento_fim'])) {
                        $query->whereBetween('pagamento', [$data['pagamento_inicio'], $data['pagamento_fim']]);
                    } else {
                        if (isset($data['pagamento_inicio'])) {
                            $query->where('pagamento', '>=', $data['pagamento_inicio']);
                        } else {
                            if (isset($data['pagamento_fim'])) {
                                $query->where('pagamento', '<=', $data['pagamento_fim']);
                            }
                        }
                    }
                } else {
                    if (isset($data['lancamento_inicio'])) {
                        if (isset($data['pagamento_inicio']) && isset($data['pagamento_fim'])) {
                            $query
                                ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                ->whereBetween('pagamento', [$data['pagamento_inicio'], $data['pagamento_fim']]);
                        } else {
                            if (isset($data['pagamento_inicio'])) {
                                $query
                                    ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                    ->where('pagamento', '>=', $data['pagamento_inicio'] . ' 00:00:00');
                            } else {
                                if (isset($data['pagamento_fim'])) {
                                    $query
                                        ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                        ->where('pagamento', '<=', $data['pagamento_fim'] . ' 23:59:59');
                                } else {
                                    $query->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00');
                                }
                            }
                        }
                    } else {
                        if (isset($data['lancamento_fim'])) {
                            $query->where('created_at', '<', $data['lancamento_fim'] . ' 23:59:59');
                            if (isset($data['pagamento_inicio']) && isset($data['pagamento_fim'])) {
                                $query->whereBetween('pagamento', [$data['pagamento_inicio'], $data['pagamento_fim']]);
                            } else {
                                if (isset($data['pagamento_inicio'])) {
                                    $query->where('pagamento', '>=', $data['pagamento_inicio'] . ' 00:00:00');
                                } else {
                                    if (isset($data['pagamento_fim'])) {
                                        $query->where('pagamento', '<=', $data['pagamento_fim'] . ' 23:59:59');
                                    }
                                }
                            }
                        }
                    }
                }

            }

        })
            ->orderBy($data['orderBy'])
            ->paginate($paginate);
        //->toSql();
        //dd($mensalidades);

    }

}
