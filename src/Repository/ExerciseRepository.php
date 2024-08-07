<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function saveExercise(Exercise $exercise): void
    {
        $this->getEntityManager()->persist($exercise);
        $this->getEntityManager()->flush();
    }

    public function delete(Exercise $exercise): void
    {
        $this->getEntityManager()->remove($exercise);
        $this->getEntityManager()->flush();
    }

    public function findOneByName(string $name, int $id): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.name = :name')
            ->andWhere('e.id != :id')
            ->setParameter('name', $name)
           ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMuscleGroup(string $muscleGroupName): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.muscleGroup', 'm')
            ->where('m.name = :name')
            ->setParameter('name', $muscleGroupName)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Exercise[] Returns an array of Exercise objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exercise
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
