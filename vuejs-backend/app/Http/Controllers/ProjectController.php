<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::with('client')->get(), 200);
    }

    public function show($id)
    {
        $project = Project::with('client')->find($id);

        if (is_null($project)) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        $project->estimation = $project->calculateEstimation();
        $project->save();

        return response()->json($project, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'estimation' => 'nullable|numeric',
        ]);
    
        $project = Project::create($validatedData);
    
        $project->estimation = $project->calculateEstimation();
        $project->save();
    
        return response()->json($project, 201);
    }
    
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
            'estimation' => 'sometimes|nullable|numeric',
        ]);
    
        $project->update($validatedData);
    
        $project->estimation = $project->calculateEstimation();
        $project->save();
    
        return response()->json($project, 200);
    }

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
