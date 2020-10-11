<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Administrador', 'email' => 'f9e848db11-6fb698@inbox.mailtrap.io', 'password' => bcrypt('12345678'), 'role' => '1', 'created_at' => new DateTime()],
            ['name' => 'Usuario Teste', 'email' => 'teste@gmail.com', 'password' => bcrypt('12345678'), 'role' => '0', 'created_at' => new DateTime()],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                $user
            ]);
        };
    }
}
