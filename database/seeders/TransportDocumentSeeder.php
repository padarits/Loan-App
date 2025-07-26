<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportDocument;

class TransportDocumentSeeder extends Seeder
{
    public function run()
    {
        // Izveidojam 100 ierakstus transporta dokumentiem
        TransportDocument::factory()->count(100)->create();
    }
}

