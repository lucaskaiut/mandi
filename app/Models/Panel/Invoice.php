<?php

namespace App\Models\Panel;

use Illuminate\Database\Eloquent\Model;
use App\Models\Panel\Customer;

class Invoice extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id', 'fornecedor_id');
    }

    public function search(Array $data, $paginate = null, $aReceber = null)
    {
        return $this->where(function ($query) use ($data) {
        //$invoice = $this->where(function ($query) use ($data) {
            if(isset($data['id'])){
                $query->where('id', $data['id']);
            }
            if (isset($data['quitada']) && $data['quitada'] != 2) {
                $query->where('quitada', $data['quitada']);
            }
            if (isset($data['lancamento_inicio']) && isset($data['lancamento_fim'])) {
                if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                    $query
                        ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                        ->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);

                } else {
                    if (!isset($data['vencimento_inicio']) && !isset($data['vencimento_fim'])) {
                        $query
                            ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59']);
                    } else {
                        if (isset($data['vencimento_inicio'])) {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('vencimento', '>=', $data['vencimento_inicio']);
                        } else {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('vencimento', '<=', $data['vencimento_fim']);
                        }
                    }
                }
            } else {

                if (!isset($data['lancamento_inicio']) && !isset($data['lancamento_fim'])) {
                    if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                        $query->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                    } else {
                        if (isset($data['vencimento_inicio'])) {
                            $query->where('vencimento', '>=', $data['vencimento_inicio']);
                        } else {
                            if (isset($data['vencimento_fim'])) {
                                $query->where('vencimento', '<=', $data['vencimento_fim']);
                            }
                        }
                    }
                } else {
                    if (isset($data['lancamento_inicio'])) {
                        if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                            $query
                                ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                ->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                        } else {
                            if (isset($data['vencimento_inicio'])) {
                                $query
                                    ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                    ->where('vencimento', '>=', $data['vencimento_inicio'] . ' 00:00:00');
                            } else {
                                if (isset($data['vencimento_fim'])) {
                                    $query
                                        ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                        ->where('vencimento', '<=', $data['vencimento_fim'] . ' 23:59:59');
                                } else {
                                    $query->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00');
                                }
                            }
                        }
                    } else {
                        if (isset($data['lancamento_fim'])) {
                            $query->where('created_at', '<', $data['lancamento_fim'] . ' 23:59:59');
                            if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                                $query->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                            } else {
                                if (isset($data['vencimento_inicio'])) {
                                    $query->where('vencimento', '>=', $data['vencimento_inicio'] . ' 00:00:00');
                                } else {
                                    if (isset($data['vencimento_fim'])) {
                                        $query->where('vencimento', '<=', $data['vencimento_fim'] . ' 23:59:59');
                                    }
                                }
                            }
                        }
                    }
                }

            }

        })
            ->where('areceber', $aReceber)
            ->orderBy($data['orderBy'])
            ->paginate($paginate);
            //->toSql();
        //dd($invoice);

    }

    public function searchPagasRecebidas(Array $data, $aReceber)
    {

        //$invoice = $this->where(function ($query) use ($data, $aReceber) {
        return $this->where(function ($query) use ($data, $aReceber) {
            if($aReceber == 1){
                $query->where('areceber', 1);
            } else {
                $query->where('areceber', 0);
            }
            if (isset($data['lancamento_inicio']) && isset($data['lancamento_fim'])) {
                if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                    $query
                        ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                        ->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);

                } else {
                    if (!isset($data['vencimento_inicio']) && !isset($data['vencimento_fim'])) {
                        $query
                            ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59']);
                    } else {
                        if (isset($data['vencimento_inicio'])) {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('vencimento', '>=', $data['vencimento_inicio']);
                        } else {
                            $query
                                ->whereBetween('created_at', [$data['lancamento_inicio'] . ' 00:00:00', $data['lancamento_fim'] . ' 23:59:59'])
                                ->where('vencimento', '<=', $data['vencimento_fim']);
                        }
                    }
                }
            } else {

                if (!isset($data['lancamento_inicio']) && !isset($data['lancamento_fim'])) {
                    if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                        $query->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                    } else {
                        if (isset($data['vencimento_inicio'])) {
                            $query->where('vencimento', '>=', $data['vencimento_inicio']);
                        } else {
                            if (isset($data['vencimento_fim'])) {
                                $query->where('vencimento', '<=', $data['vencimento_fim']);
                            }
                        }
                    }
                } else {
                    if (isset($data['lancamento_inicio'])) {
                        if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                            $query
                                ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                ->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                        } else {
                            if (isset($data['vencimento_inicio'])) {
                                $query
                                    ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                    ->where('vencimento', '>=', $data['vencimento_inicio'] . ' 00:00:00');
                            } else {
                                if (isset($data['vencimento_fim'])) {
                                    $query
                                        ->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00')
                                        ->where('vencimento', '<=', $data['vencimento_fim'] . ' 23:59:59');
                                } else {
                                    $query->where('created_at', '>', $data['lancamento_inicio'] . ' 00:00:00');
                                }
                            }
                        }
                    } else {
                        if (isset($data['lancamento_fim'])) {
                            $query->where('created_at', '<', $data['lancamento_fim'] . ' 23:59:59');
                            if (isset($data['vencimento_inicio']) && isset($data['vencimento_fim'])) {
                                $query->whereBetween('vencimento', [$data['vencimento_inicio'], $data['vencimento_fim']]);
                            } else {
                                if (isset($data['vencimento_inicio'])) {
                                    $query->where('vencimento', '>=', $data['vencimento_inicio'] . ' 00:00:00');
                                } else {
                                    if (isset($data['vencimento_fim'])) {
                                        $query->where('vencimento', '<=', $data['vencimento_fim'] . ' 23:59:59');
                                    }
                                }
                            }
                        }
                    }
                }

            }

        })
            ->where('quitada', 1)
            ->orderBy($data['orderBy'])
            ->get();
        //->toSql();
        //dd($invoice);

    }

}

