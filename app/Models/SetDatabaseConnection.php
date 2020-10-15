<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SetDatabaseConnection extends Model
{
    public function setConnection($params)
    {
        config(['database.connections.mysql2' => [
            'driver' => $params['driver'],
            'host' => $params['host'],
            'port' => $params['port'],
            'database' => $params['database'],
            'username' => $params['username'],
            'password' => $params['password']
        ]]);

        return DB::connection('mysql2');
    }
}
