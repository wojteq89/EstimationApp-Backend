<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Pobierz wszystkie klienty
    public function index()
    {
        return response()->json(Client::all(), 200);
    }

    // Pobierz konkretnego klienta
    public function show($id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return response()->json($client, 200);
    }

    // Dodaj nowego klienta
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
        ]);

        $client = Client::create($validatedData);

        return response()->json($client, 201);
    }

    // Zaktualizuj istniejącego klienta
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (is_null($client)) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:clients,email,' . $id,
        ]);

        $client->update($validatedData);

        return response()->json($client, 200);
    }

    // Usuń klienta
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
