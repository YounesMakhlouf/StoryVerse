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
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }
    #[Route("/chart-data", name:"chart_data")]

    public function chartData(): JsonResponse
    {

        $queryBuilder = $this->userRepository->createQueryBuilder('u');
        $queryBuilder
            ->select('COUNT(u.id) as count')
            ->addSelect('u.gender')
            ->groupBy('u.gender');

        $results = $queryBuilder->getQuery()->getResult();
 $data =[
    'labels'=>["Male", "Female"],
    'datasets'=>
        [
            'data'=> [$results[0]['count'] , $results[1]['count'] ],
            'backgroundColor'=> ["#89CFF0", "#f4c2c2"]  ]   ];

        return new JsonResponse($data);
    }


    #[Route("/language", name:"app_language")]

    public function LangChart(StoryRepository $storyRepository): Response{

        $queryBuilder = $storyRepository->createQueryBuilder('s');
        $queryBuilder
            ->select('COUNT(s.id) as count')
            ->addSelect('s.language')
            ->groupBy('s.language');
        $results = $queryBuilder->getQuery()->getResult();
        $countTable = array();
        $languageTable = array();

        foreach ($results as $result) {
            $countTable[] = $result['count'];
            $languageTable[] = $result['language'];
        }

        $data = array(
            'count' => $countTable,
            'language' => $languageTable
        );

        return new JsonResponse($data);

    }}