<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessingResult;

class ProcessingResultController extends Controller
{
    // Show a single processing result
    public function show(Request $request) //$uuid
    {
        $uuid = null;
        try{
            $request->validate([
                'uuid' => 'required|string',
            ]);

            $uuid = $request->query('uuid');
            $result = ProcessingResult::where('uuid', $uuid)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'responce'=>'uuid is empty or not exists', 'uuid'=>$uuid], 404); // Return empty JSON with 404 status
        }

        return response()->json($result);
    }

    // Store a new processing result
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid' => 'required|uuid|unique:processing_results,uuid',
            'processing_status' => 'required|string',
            'processing_message' => 'required|string',
        ]);

        $result = ProcessingResult::create($validated);
        return response()->json($result, 201);
    }

    // Update an existing processing result
    public function update(Request $request, $uuid)
    {
        $result = ProcessingResult::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'processing_status' => 'sometimes|string',
            'processing_message' => 'sometimes|string',
        ]);

        $result->update($validated);
        return response()->json($result);
    }
}
