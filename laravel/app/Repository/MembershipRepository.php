<?php

namespace App\Repository;

use App\Models\Membership;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MembershipRepository
{
    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param array $params
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getMemberships(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Membership::with(['client', 'trainingProgram']);

        // точний збіг
        foreach (['id', 'client_id', 'training_program_id'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['start_date', 'end_date', 'status'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, 'LIKE', '%' . $params[$field] . '%');
            }
        }

        return $query->paginate($itemsPerPage);
    }
}
