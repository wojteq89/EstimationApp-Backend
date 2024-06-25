<?php

namespace App\Http\Controllers;

use App\Models\Estimation;
use App\Models\Project;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    public function index()
    {
        return response()->json(Estimation::with('project.client')->get(), 200);
    }

    public function show($id)
    {
        $estimation = Estimation::with('project.client')->find($id);

        if (is_null($estimation)) {
            return response()->json(['message' => 'Estimation not found'], 404);
        }

        return response()->json($estimation, 200);
    }

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
    
        $project = Project::findOrFail($validatedData['project_id']);
    
        $estimation = Estimation::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'project_id' => $validatedData['project_id'],
            'client_id' => $project->client_id,
            'date' => $validatedData['date'],
            'type' => $validatedData['type'],
            'amount' => $validatedData['amount'],
        ]);
    
        return response()->json($estimation, 201);
    }


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
            'client_id' => 'required|exists:clients,id',
            'date' => 'sometimes|required|date',
            'type' => 'sometimes|required|in:hourly,fixed',
            'amount' => 'sometimes|required|numeric',
        ]);

        $estimation->update($validatedData);

        return response()->json($estimation, 200);
    }

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
