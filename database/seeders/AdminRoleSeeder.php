<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Pievieno admin lomu, ja tā vēl nav izveidota
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['uuid' => (string) Str::uuid(),'name' => 'admin']);
        }
        // Pievieno transport lomu, ja tā vēl nav izveidota
        if (Role::where('name', 'transport')->doesntExist()) {
            Role::create(['uuid' => (string) Str::uuid(),'name' => 'transport']);
        }
    }
}
