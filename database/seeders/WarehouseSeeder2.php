<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'Otra Noliktava', // Piemērs nosaukumam, vari mainīt pēc vajadzības
            'location' => 'Unknown',       // Piemērs lokācijai
            'warehouse_code' => 'otra',    // Noliktavas kods 'none'
            'user_guid' => null,           // Ja nepieciešams sasaistīt ar lietotāju, piešķir GUID, pretējā gadījumā atstāj null
        ]);
    }
}
