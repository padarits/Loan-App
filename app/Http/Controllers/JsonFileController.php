<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JsonFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JsonFileController extends Controller
{
    /**
     * Saglabā JSON datus datubāzē.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validē pieprasījumu, lai pārliecinātos, ka `data` lauks ir JSON formātā
        $validator = Validator::make($request->all(), [
            'data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nepareizs formāts.',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // Pārveido `data` par masīvu, lai varētu pārbaudīt `data-type` lauku
        $data = json_decode($request->data, true);

        // Pārbauda, vai `data` satur `data-type` lauku
        if (!array_key_exists('data-type', $data)) {
            return response()->json([
                'success' => false,
                'message' => 'Trūkst nepieciešamais "data-type" lauks JSON datos.',
            ], 422);
        }

        // Izveido un saglabā jaunu JSON faila ierakstu
        $jsonFile = JsonFile::create([
            'data' => json_decode($request->data, true), // Pārvērš JSON datus par masīvu
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dati veiksmīgi saglabāti.',
            'data' => $jsonFile,
        ], 201);
    }
}
