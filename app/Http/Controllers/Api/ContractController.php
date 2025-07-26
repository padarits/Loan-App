<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PowerBiContract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function store(Request $request)
    {
        // Validējam ienākošo pieprasījumu
        $request->validate([
            '*.pircjs' => 'required|string', // pircējs
            '*.ligums' => 'required|string', // līgums
            '*.noslegsanas_datums' => 'required|date', // noslēgšanas datums
            '*.m3_akt_uzdots' => 'nullable|numeric', // m3 akt. uzdots
            '*.m3_nom_uzdots' => 'nullable|numeric', // m3 nom. uzdots
            '*.m3_akt_piegadats' => 'nullable|numeric', // m3 akt. piegādāts
            '*.m3_nom_piegadats' => 'nullable|numeric', // m3 nom. piegādāts
            '*.m3_akt_osta' => 'nullable|numeric', // m3 akt. osta
            '*.m3_nom_osta' => 'nullable|numeric', // m3 nom. osta
            '*.m3_akt_rupnica' => 'nullable|numeric', // m3 akt. rūpnīcā
            '*.m3_nom_rupnica' => 'nullable|numeric', // m3 nom. rūpnīcā
            '*.cena_par_nom' => 'nullable|numeric', // cena par nom.
            '*.cena_par_akt' => 'nullable|numeric', // cena par akt.
            '*.valuta' => 'nullable|string', // valūta
            '*.cena_fraht' => 'nullable|numeric', // cena fraht
            '*.valuta_fraht' => 'nullable|string', // valūta fraht
            '*.termins' => 'nullable|date', // termiņš
            '*.piegades_nosacijumi' => 'nullable|string', // piegādes nosacījumi
            '*.osta' => 'nullable|string', // osta
        ]);

        // Nodzēšam visus datus no tabulas
        PowerBiContract::truncate();
        
        // Saglabājam datus
        foreach ($request->all() as $contractData) {
            PowerBiContract::create($contractData);
        }

        return response()->json(['message' => 'Dati veiksmīgi izdzēsti un pievienoti no jauna.'], 201);
    }
}
