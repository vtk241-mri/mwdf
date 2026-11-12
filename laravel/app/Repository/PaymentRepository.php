<?php

namespace App\Repository;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentRepository
{
    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param array $params
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getPayments(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Payment::with('membership');

        // точний збіг
        foreach (['id', 'membership_id', 'amount'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['method', 'paid_at'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, 'LIKE', '%' . $params[$field] . '%');
            }
        }

        return $query->paginate($itemsPerPage);
    }
}
