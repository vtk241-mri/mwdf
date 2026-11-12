<?php

namespace App\Repository;

use App\Models\Trainer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TrainerRepository
{
    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param array $params
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getTrainers(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Trainer::query();

        // точний збіг
        foreach (['id'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['first_name', 'last_name', 'email', 'phone', 'specialization'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, 'LIKE', '%' . $params[$field] . '%');
            }
        }

        return $query->paginate($itemsPerPage);
    }
}
