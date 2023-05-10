<?php

namespace App\Repository;

use App\Entity\Tier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tier>
 *
 * @method Tier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tier[]    findAll()
 * @method Tier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tier::class);
    }

    public function save(Tier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByXpThreshold()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.xpThreshold', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findNextTier(int $xp)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.xpThreshold > :xp')
            ->setParameter('xp', $xp)
            ->orderBy('t.xpThreshold', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


//    /**
//     * @return Tier[] Returns an array of Tier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tier
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
