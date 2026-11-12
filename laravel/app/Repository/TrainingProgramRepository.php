<?php

namespace App\Repository;

use App\Models\TrainingProgram;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TrainingProgramRepository
{
    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param array $params
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getTrainingPrograms(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = TrainingProgram::with('trainer');

        // точний збіг
        foreach (['id', 'trainer_id', 'duration_weeks', 'price'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['name', 'description'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, 'LIKE', '%' . $params[$field] . '%');
            }
        }

        return $query->paginate($itemsPerPage);
    }
}
