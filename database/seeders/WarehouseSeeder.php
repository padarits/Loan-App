<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::create([
            'name' => 'Visas Noliktavas', // Piemērs nosaukumam, vari mainīt pēc vajadzības
            'location' => 'Unknown',       // Piemērs lokācijai
            'warehouse_code' => 'none',    // Noliktavas kods 'none'
            'user_guid' => null,           // Ja nepieciešams sasaistīt ar lietotāju, piešķir GUID, pretējā gadījumā atstāj null
        ]);
    }
}
