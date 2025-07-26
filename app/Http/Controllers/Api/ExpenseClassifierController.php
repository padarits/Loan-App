<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseClassifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExpenseClassifierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return all expense classifiers
        return response()->json(ExpenseClassifier::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            // Validate the request data
            $validated = $request->validate([
                'id' => 'nullable|uuid|exists:expense_classifiers,id',
                'parent_id' => 'nullable|uuid|exists:expense_classifiers,id',
                'code' => 'required|string|unique:expense_classifiers,code',
                'name' => 'required|string|max:255',
                'name_for_search' => 'nullable|string|max:255',
            ]);
            
            // Create a new ExpenseClassifier
            $expenseClassifier = ExpenseClassifier::create($validated);
            // Return the created classifier
            return response()->json($expenseClassifier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the expense classifier by ID
        $expenseClassifier = ExpenseClassifier::findOrFail($id);

        return response()->json($expenseClassifier, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the expense classifier
        $expenseClassifier = ExpenseClassifier::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'parent_id' => 'nullable|uuid|exists:expense_classifiers,id',
            'code' => 'nullable|string|unique:expense_classifiers,code,' . $id,
            'name' => 'nullable|string|max:255',
            'name_for_search' => 'nullable|string|max:255',
        ]);

        // Update the classifier
        $expenseClassifier->update($validated);

        return response()->json($expenseClassifier, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the expense classifier
        $expenseClassifier = ExpenseClassifier::findOrFail($id);

        // Delete the classifier
        $expenseClassifier->delete();

        return response()->json(null, 204);
    }
}
