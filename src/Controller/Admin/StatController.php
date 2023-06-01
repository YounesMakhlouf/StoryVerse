<?php

namespace App\Controller\Admin;

use App\Repository\StoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    #[Route("/chart-data", name: "chart_data")]
    public function genderChart(UserRepository $userRepository): JsonResponse
    {
        $results = $this->getResult($userRepository, 'u', 'COUNT(u.id) as count', 'u.gender', 'u.gender');
        return $this->prepareChartData($results);
    }

    private function getResult($repository, $alias, $select1, $select2, $groupBy)
    {
        $queryBuilder = $repository->createQueryBuilder($alias);
        $queryBuilder
            ->select($select1)
            ->addSelect($select2 . ' as data')
            ->groupBy($groupBy);

        return $queryBuilder->getQuery()->getResult();
    }

    private function prepareChartData($results): JsonResponse
    {
        $data = [];
        foreach ($results as $result) {
            $data['data'][] = $result['count'];
            $data['labels'][] = $result['data'];
        }

        return new JsonResponse($data);
    }

    #[Route("/language", name: "app_language")]
    public function langChart(StoryRepository $storyRepository): Response
    {
        $results = $this->getResult($storyRepository, 's', 'COUNT(s.id) as count', 's.language', 's.language');
        return $this->prepareChartData($results);
    }

    #[Route("/genre", name: "app_genre")]
    public function genreChart(StoryRepository $storyRepository): Response
    {
        $results = $this->getResult($storyRepository, 's', 'COUNT(s.id) as count', 's.genre', 's.genre');
        return $this->prepareChartData($results);
    }
}