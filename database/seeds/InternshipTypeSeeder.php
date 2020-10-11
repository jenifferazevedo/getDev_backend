<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternshipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ["Estágio Curricular", "Estágio Extracurricular", "Estágio Profissional", "outro"];
        foreach ($types as $type) {
            DB::table('internship_types')->insert([
                'name' => $type,
                'created_at' => new DateTime(),
            ]);
        }
    }
}
