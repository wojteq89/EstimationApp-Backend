<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    // Pobierz wszystkie wyceny
    public function index()
    {
        return response()->json(Estimation::with('project.client')->get(), 200);
    }

    // Pobierz konkretną wycenę
    public function show($id)
    {
        $estimation = Estimation::with('project.client')->find($id);

        if (is_null($estimation)) {
            return response()->json(['message' => 'Estimation not found'], 404);
        }

        return response()->json($estimation, 200);
    }

    // Dodaj nową wycenę
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date',
            'type' => 'required|in:hourly,fixed',
            'amount' => 'required|numeric',
        ]);

        $estimation = Estimation::create($validatedData);

        return response()->json($estimation, 201);
    }

    // Zaktualizuj istniejącą wycenę
    public function update(Request $request, $id)
    {
        $estimation = Estimation::find($id);

        if (is_null($estimation)) {
            return response()->json(['message' => 'Estimation not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'project_id' => 'sometimes|required|exists:projects,id',
            'date' => 'sometimes|required|date',
            'type' => 'sometimes|required|in:hourly,fixed',
            'amount' => 'sometimes|required|numeric',
        ]);

        $estimation->update($validatedData);

        return response()->json($estimation, 200);
    }

    // Usuń wycenę
    public function destroy($id)
    {
        $estimation = Estimation::find($id);

        if (is_null($estimation)) {
            return response()->json(['message' => 'Estimation not found'], 404);
        }

        $estimation->delete();

        return response()->json(['message' => 'Estimation deleted'], 204);
    }
}
