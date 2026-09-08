<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\TheDivision;

class TheDivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $divisions = [
            ["divisionname" => "Project Development Division"],
            ["divisionname" => "Knowledge Management Division"],
            ["divisionname" => "Project and Research Division"],
            ["divisionname" => "Policy Formulations Division"]
        ];


        foreach($divisions as $d) {
            TheDivision::create($d);
        }
    }
}
