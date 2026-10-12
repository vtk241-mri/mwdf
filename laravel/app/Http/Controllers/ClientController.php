<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    /**
     * READ - список усіх клієнтів
     *
     * @return mixed
     */
    public function getClients(): mixed
    {
        $clients = Client::all();

        return response()->json(['data' => $clients], Response::HTTP_OK);
    }

    /**
     * READ - один клієнт за id
     *
     * @param string $id
     * @return mixed
     */
    public function getClientItem(string $id): mixed
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нового клієнта
     *
     * @param Request $request
     * @return mixed
     */
    public function createClient(Request $request): mixed
    {
        $client = Client::create($request->all());

        return response()->json(['data' => $client], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити клієнта за id
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateClient(Request $request, string $id): mixed
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $client->update($request->all());

        return response()->json(['data' => $client], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити клієнта за id
     *
     * @param string $id
     * @return mixed
     */
    public function deleteClient(string $id): mixed
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['data' => ['error' => 'Not found client by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $client->delete();

        return response()->json(['data' => 'Deleted client by id ' . $id], Response::HTTP_OK);
    }
}
