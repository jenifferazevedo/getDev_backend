<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Accepted', 'Rejected', 'Waiting'
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insert([
                'name' => $status,
                'created_at' => new DateTime(),
            ]);
        }
    }
}
