<?php

namespace App\Repository;

use App\Entity\Trainer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trainer>
 */
class TrainerRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trainer::class);
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
        $queryBuilder = $this->createQueryBuilder('trainer');

        // точний збіг
        foreach (['id'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("trainer.$field = :$field")
                    ->setParameter($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['firstName', 'lastName', 'email', 'phone', 'specialization'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("trainer.$field LIKE :$field")
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
