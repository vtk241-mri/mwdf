<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Membership;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MembershipController extends Controller
{
    /**
     * READ - список усіх абонементів (разом з клієнтом і програмою)
     *
     * @return mixed
     */
    public function getMemberships(): mixed
    {
        $memberships = Membership::with(['client', 'trainingProgram'])->get();

        return response()->json(['data' => $memberships], Response::HTTP_OK);
    }

    /**
     * READ - один абонемент за id (разом з клієнтом і програмою)
     *
     * @param string $id
     * @return mixed
     */
    public function getMembershipItem(string $id): mixed
    {
        $membership = Membership::with(['client', 'trainingProgram'])->find($id);

        if (!$membership) {
            return response()->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $membership], Response::HTTP_OK);
    }

    /**
     * CREATE - створити новий абонемент (записати клієнта на програму)
     *
     * @param Request $request
     * @return mixed
     */
    public function createMembership(Request $request): mixed
    {
        $client = Client::find($request->input('client_id'));

        if (!$client) {
            return response()->json(['data' => ['error' => 'Not found client by id ' . $request->input('client_id')]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram = TrainingProgram::find($request->input('training_program_id'));

        if (!$trainingProgram) {
            return response()->json(['data' => ['error' => 'Not found training program by id ' . $request->input('training_program_id')]], Response::HTTP_NOT_FOUND);
        }

        $membership = Membership::create($request->all());

        return response()->json(['data' => $membership->load(['client', 'trainingProgram'])], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити абонемент за id
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateMembership(Request $request, string $id): mixed
    {
        $membership = Membership::find($id);

        if (!$membership) {
            return response()->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('client_id') && !Client::find($request->input('client_id'))) {
            return response()->json(['data' => ['error' => 'Not found client by id ' . $request->input('client_id')]], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('training_program_id') && !TrainingProgram::find($request->input('training_program_id'))) {
            return response()->json(['data' => ['error' => 'Not found training program by id ' . $request->input('training_program_id')]], Response::HTTP_NOT_FOUND);
        }

        $membership->update($request->all());

        return response()->json(['data' => $membership->load(['client', 'trainingProgram'])], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити абонемент за id
     *
     * @param string $id
     * @return mixed
     */
    public function deleteMembership(string $id): mixed
    {
        $membership = Membership::find($id);

        if (!$membership) {
            return response()->json(['data' => ['error' => 'Not found membership by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $membership->delete();

        return response()->json(['data' => 'Deleted membership by id ' . $id], Response::HTTP_OK);
    }
}
