<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::create(
            [
                'id' => 1,
                'name' => 'superadmin',
                'assignable' => false,
                'guard_name' => 'web',
            ]
            );


        Roles::create(
            [
                'id' => 2,
                'name' => 'admin',
                'assignable' => true,
                'guard_name' => 'web',
            ]
        );

    }
}