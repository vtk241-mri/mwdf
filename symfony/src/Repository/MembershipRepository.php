<?php

namespace App\Repository;

use App\Entity\Membership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Membership>
 */
class MembershipRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membership::class);
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
        $queryBuilder = $this->createQueryBuilder('membership');

        // точний збіг
        foreach (['id', 'client', 'trainingProgram'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("membership.$field = :$field")
                    ->setParameter($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['startDate', 'endDate', 'status'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("membership.$field LIKE :$field")
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
