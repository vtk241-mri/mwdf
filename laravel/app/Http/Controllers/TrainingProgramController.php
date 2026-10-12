<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrainingProgramController extends Controller
{
    /**
     * READ - список усіх програм тренувань (разом з тренером)
     *
     * @return mixed
     */
    public function getTrainingPrograms(): mixed
    {
        $trainingPrograms = TrainingProgram::with('trainer')->get();

        return response()->json(['data' => $trainingPrograms], Response::HTTP_OK);
    }

    /**
     * READ - одна програма тренувань за id (разом з тренером)
     *
     * @param string $id
     * @return mixed
     */
    public function getTrainingProgramItem(string $id): mixed
    {
        $trainingProgram = TrainingProgram::with('trainer')->find($id);

        if (!$trainingProgram) {
            return response()->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $trainingProgram], Response::HTTP_OK);
    }

    /**
     * CREATE - створити нову програму тренувань
     *
     * @param Request $request
     * @return mixed
     */
    public function createTrainingProgram(Request $request): mixed
    {
        $trainer = Trainer::find($request->input('trainer_id'));

        if (!$trainer) {
            return response()->json(['data' => ['error' => 'Not found trainer by id ' . $request->input('trainer_id')]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram = TrainingProgram::create($request->all());

        return response()->json(['data' => $trainingProgram->load('trainer')], Response::HTTP_CREATED);
    }

    /**
     * UPDATE - оновити програму тренувань за id
     *
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateTrainingProgram(Request $request, string $id): mixed
    {
        $trainingProgram = TrainingProgram::find($id);

        if (!$trainingProgram) {
            return response()->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('trainer_id') && !Trainer::find($request->input('trainer_id'))) {
            return response()->json(['data' => ['error' => 'Not found trainer by id ' . $request->input('trainer_id')]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram->update($request->all());

        return response()->json(['data' => $trainingProgram->load('trainer')], Response::HTTP_OK);
    }

    /**
     * DELETE - видалити програму тренувань за id
     *
     * @param string $id
     * @return mixed
     */
    public function deleteTrainingProgram(string $id): mixed
    {
        $trainingProgram = TrainingProgram::find($id);

        if (!$trainingProgram) {
            return response()->json(['data' => ['error' => 'Not found training program by id ' . $id]], Response::HTTP_NOT_FOUND);
        }

        $trainingProgram->delete();

        return response()->json(['data' => 'Deleted training program by id ' . $id], Response::HTTP_OK);
    }
}
