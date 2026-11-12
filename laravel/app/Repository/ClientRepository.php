<?php

namespace App\Repository;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository
{
    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param array $params
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getClients(array $params, int $itemsPerPage): LengthAwarePaginator
    {
        $query = Client::query();

        // точний збіг
        foreach (['id'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['first_name', 'last_name', 'email', 'phone', 'birth_date'] as $field) {
            if (isset($params[$field])) {
                $query->where($field, 'LIKE', '%' . $params[$field] . '%');
            }
        }

        return $query->paginate($itemsPerPage);
    }
}
