<?php

namespace App\Repository;

use App\Entity\Story;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
    public function __construct(ManagerRegistry $registry, private readonly EntityManagerInterface $entityManager)
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
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @throws Exception
     */
    public function deleteStoryWithContributionsAndComments(int $storyId): void
    {
        $story = $this->find($storyId);

        if (!$story) {
            throw new Exception('Story not found.');
        }

        $this->deleteContributions($story);
        $this->deleteComments($story);

        $this->entityManager->remove($story);
        $this->entityManager->flush();
    }

    private function deleteContributions(Story $story): void
    {
        foreach ($story->getContributions() as $contribution) {
            $this->entityManager->remove($contribution);
        }
    }

    public function remove(Story $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function deleteComments(Story $story): void
    {
        foreach ($story->getComments() as $comment) {
            $this->entityManager->remove($comment);
        }
    }

    public function searchByTitle(string $searchQuery): array
    {
        return $this->createQueryBuilder('s')
            ->where('LOWER(s.title) LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . strtolower($searchQuery) . '%')
            ->getQuery()
            ->getResult();
    }
}