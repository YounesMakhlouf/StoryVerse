<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\StoryRepository;
use Doctrine\DBAL\Query;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class StatController extends AbstractController
{
    #[Route("/chart-data", name:"chart_data")]

    public function GenderChart(UserRepository $userRepository): JsonResponse
    {
        $results = $this->getResult($userRepository, 'u', 'COUNT(u.id) as count', 'u.gender', 'u.gender');
        return $this->PrepareChartData($results);
    }

    #[Route("/language", name:"app_language")]

    public function LangChart(StoryRepository $storyRepository): Response{

        $results = $this->getResult($storyRepository,'s','COUNT(s.id) as count','s.language','s.language');
        return $this->PrepareChartData($results);

    }
    #[Route("/genre", name:"app_genre")]

    public function genreChart(StoryRepository $storyRepository): Response{

        $results = $this
            ->getResult($storyRepository,'s','COUNT(s.id) as count','s.genre','s.genre');
        return $this->PrepareChartData($results);
    }

public function getResult($repository,$alias,$select1,$select2,$groupBy){
    $queryBuilder = $repository->createQueryBuilder($alias);
    $queryBuilder
        ->select($select1)
        ->addSelect($select2)
        ->groupBy($groupBy);

    return $queryBuilder->getQuery()->getResult();
}

public function PrepareChartData($results): JsonResponse
{
    $countTable = array();
    $dataTable = array();

    foreach ($results as $result) {
        $countTable[] = $result['count'];
        $dataTable[] = $result['language'];
    }

    $data = array(
        'data' => $countTable,
        'labels' => $dataTable
    );

    return new JsonResponse($data);
}
}