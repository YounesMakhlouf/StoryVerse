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

    public function findAllOrderedByXpThresholdAscending(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.xpThreshold', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findNextTier(int $xp): ?Tier
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.xpThreshold > :xp')
            ->setParameter('xp', $xp)
            ->orderBy('t.xpThreshold', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
