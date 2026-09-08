<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\divbelongto;
 
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::updateOrCreate(
            ['email' => 'alvin@merto.com'],
            [
                'name'              => 'Alvin',
                'email_verified_at' => now(),
                'remember_token'    => null,
                'password'          => bcrypt('ghty56rueiwoqp'),
            ]
        );
    }
}
