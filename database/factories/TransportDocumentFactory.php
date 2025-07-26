<?php

namespace Database\Factories;

use App\Models\TransportDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransportDocumentFactory extends Factory
{
    protected $model = TransportDocument::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(), // UUID ģenerēšana primārajai atslēgai
            'document_number' => $this->faker->unique()->numerify('DOC-#####'),
            'document_date' => $this->faker->date('d.m.Y'),
            'supplier_name' => $this->faker->company(),
            'supplier_reg_number' => $this->faker->numerify('########'),
            'supplier_address' => $this->faker->address(),
            'receiver_name' => $this->faker->company(),
            'receiver_reg_number' => $this->faker->numerify('########'),
            'receiver_address' => $this->faker->address(),
            'issuer_name' => $this->faker->name(),
            'receiver_person_name' => $this->faker->name(), // Pievienojam šo lauku
            'receiving_location' => $this->faker->city(),
            'additional_info' => $this->faker->sentence(),
        ];
    }
}


