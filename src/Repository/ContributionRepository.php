<?php

namespace App\Repository;

use App\Entity\Contribution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contribution>
 *
 * @method Contribution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contribution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contribution[]    findAll()
 * @method Contribution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContributionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contribution::class);
    }

    public function save(Contribution $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contribution $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
