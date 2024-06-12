<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all(), 200);
    }

    public function show($id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'country' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
        ]);

        $validatedData['logo'] = $request->input('logo', '');

        $client = Client::create($validatedData);

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);
    
        if (is_null($client)) {
            return response()->json(['message' => 'Client not found'], 404);
        }
    
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'logo' => 'sometimes|nullable|string',
            'country' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:clients,email,' . $id,
        ]);
    
        $validatedData['logo'] = $request->input('logo', '');
    
        $client->update($validatedData);
    
        return response()->json($client, 200);
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted'], 204);
    }
}
