<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransportDocument;
use App\Models\TransportDocumentLine;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransportDocumentLinesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Iegūst visus transporta dokumentus
        $documents = TransportDocument::all();

        // Katram dokumentam pievieno nejaušas rindas
        foreach ($documents as $document) {
            // Pieņemsim, ka katram dokumentam būs no 1 līdz 15 līnijām
            $lineCount = rand(1, 15);

            for ($i = 0; $i < $lineCount; $i++) {
                $quantity = $faker->numberBetween(1, 100);  // Nejaušs daudzums
                $price = $faker->randomFloat(2, 10, 500);   // Nejauša cena
            
                TransportDocumentLine::create([
                    'transport_document_id' => $document->id,  // UUID no TransportDocument
                    'product_code' => $faker->bothify('P#####'),  // Nejaušs produkta kods, piemēram, P12345
                    'product_name' => $faker->word,  // Nejaušs produkta nosaukums
                    'quantity' => $quantity,  // Daudzums
                    'price' => $price,  // Cena
                    'total' => $quantity * $price,  // Tieši aprēķināta summa
                ]);
            }
        }
    }
}


