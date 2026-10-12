<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrainerController extends Controller
{
    /**
     * READ - список усіх тренерів
     *
     * @return mixed
     */
    public function getTrainers(): mixed
    {
        $trainers = Trainer::all();

        return response()->json(['data' => $trainers], Response::HTTP_OK);
    }

    /**
     * READ - один тренер за id
     *
     * @param string $id
     * @return mixed
     */
    public function getTrainerItem(string $id): mixed
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            return response()->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $trainer], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нового тренера
     *
     * @param Request $request
     * @return mixed
     */
    public function createTrainer(Request $request): mixed
    {
        $trainer = Trainer::create($request->all());

        return response()->json(['data' => $trainer], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити тренера за id
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateTrainer(Request $request, string $id): mixed
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            return response()->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $trainer->update($request->all());

        return response()->json(['data' => $trainer], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити тренера за id
     *
     * @param string $id
     * @return mixed
     */
    public function deleteTrainer(string $id): mixed
    {
        $trainer = Trainer::find($id);

        if (!$trainer) {
            return response()->json(['data' => ['error' => 'Not found trainer by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $trainer->delete();

        return response()->json(['data' => 'Deleted trainer by id ' . $id], Response::HTTP_OK);
    }
}
