<?php

namespace App\Repository;

use App\Entity\TrainingProgram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingProgram>
 */
class TrainingProgramRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingProgram::class);
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
        $queryBuilder = $this->createQueryBuilder('trainingProgram');

        // точний збіг
        foreach (['id', 'trainer', 'durationWeeks', 'price'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("trainingProgram.$field = :$field")
                    ->setParameter($field, $params[$field]);
            }
        }

        // частковий збіг
        foreach (['name', 'description'] as $field) {
            if (isset($params[$field])) {
                $queryBuilder
                    ->andWhere("trainingProgram.$field LIKE :$field")
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
