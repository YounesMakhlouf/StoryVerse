<?php

namespace App\Repository;

use App\Entity\Story;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Story>
 *
 * @method Story|null find($id, $lockMode = null, $lockVersion = null)
 * @method Story|null findOneBy(array $criteria, array $orderBy = null)
 * @method Story[]    findAll()
 * @method Story[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Story::class);
    }

    public function save(Story $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Story $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createOrderedByLikesQueryBuilder(string $genre = null): QueryBuilder
    {
        $queryBuilder = $this->addOrderByLikesQueryBuilder();

        if ($genre) {
            $queryBuilder->andWhere('story.genre = :genre')
                ->setParameter('genre', $genre);
        }

        return $queryBuilder;
    }

    private function addOrderByLikesQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        $queryBuilder = $queryBuilder ?? $this->createQueryBuilder('story');
        return $queryBuilder
            ->select('story, COUNT(l.id) AS HIDDEN like_count')
            ->leftJoin('story.likes', 'l')
            ->groupBy('story.id')
            ->orderBy('like_count', 'DESC');
    }

    public function findAllOrderedByLikes(string $genre = null): array
    {
        $queryBuilder = $this->addOrderByLikesQueryBuilder();
        if ($genre) {
            $queryBuilder->andWhere("mix.genre = :genre")
                ->setParameter('genre', $genre);
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Story
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
