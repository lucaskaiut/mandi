<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'suporte',
            'email' => 'suporte@gmail.com',
            'password' => bcrypt('teste'),
        ]);

        DB::table('payment_methods')->insert([
            'name' => 'Dinheiro',
            'categoria' => 'dinheiro',
        ]);

        DB::table('payment_methods')->insert([
            'name' => 'Depósito',
            'categoria' => 'bancaria',
        ]);

        DB::table('payment_methods')->insert([
            'name' => 'Transferência',
            'categoria' => 'bancaria',
        ]);

        DB::table('payment_methods')->insert([
            'name' => 'Cartão',
            'categoria' => 'cartao',
        ]);

        DB::table('settings')->insert([
            'baixa_mensalidade_caixa_fechado' => '0',
        ]);

        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'athlete-list',
            'athlete-create',
            'athlete-edit',
            'athlete-delete',
            'fin-list',
            'fin-create',
            'fin-edit',
            'fin-delete',
            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
        ];


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $firstUser = User::find(1);

        $firstUser->givePermissionTo($permissions);

    }
}