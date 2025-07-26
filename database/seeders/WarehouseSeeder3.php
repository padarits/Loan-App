<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'Treša Noliktava', // Piemērs nosaukumam, vari mainīt pēc vajadzības
            'location' => 'Unknown',       // Piemērs lokācijai
            'warehouse_code' => 'tresa',    // Noliktavas kods 'none'
            'user_guid' => null,           // Ja nepieciešams sasaistīt ar lietotāju, piešķir GUID, pretējā gadījumā atstāj null
        ]);
    }
}
