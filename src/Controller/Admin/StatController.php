<?php

namespace App\Controller\Admin;

use App\Entity\User;
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

}