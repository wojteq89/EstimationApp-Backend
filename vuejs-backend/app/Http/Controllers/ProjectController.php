<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Pobierz wszystkie projekty
    public function index()
    {
        return response()->json(Project::with('client')->get(), 200);
    }

    // Pobierz konkretny projekt
    public function show($id)
    {
        $project = Project::with('client')->find($id);

        if (is_null($project)) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project, 200);
    }

    // Dodaj nowy projekt
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
        ]);

        $project = Project::create($validatedData);

        return response()->json($project, 201);
    }

    // Zaktualizuj istniejący projekt
    public function update(Request $request, $id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'client_id' => 'sometimes|required|exists:clients,id',
        ]);

        $project->update($validatedData);

        return response()->json($project, 200);
    }

    // Usuń projekt
    public function destroy($id)
    {
        $project = Project::find($id);

        if (is_null($project)) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted'], 204);
    }
}
