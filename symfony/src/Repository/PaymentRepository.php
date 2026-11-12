<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * Фільтрація по кожному полю + пагінація
     *
     * @param int $page
     * @param int $itemsPerPage
     * @param array $params
     * @return array
     */
    public function findAllByParams(int $page, int $itemsPerPage, array $params = []): array
    {
        $queryBuilder = $this->createQueryBuilder('payment');

        // точний збіг
        foreach (['id', 'membership', 'amount'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("payment.$field = :$field")
                    ->setParameter($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['method', 'paidAt'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("payment.$field LIKE :$field")
                    ->setParameter($field, '%' . $params[$field] . '%');
            }
        }

        return $queryBuilder
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->getQuery()
            ->getResult();
    }
}
