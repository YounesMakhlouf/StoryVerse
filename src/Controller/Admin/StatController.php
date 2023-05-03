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

    public function chartData(UserRepository $userRepository): JsonResponse
    {
        $results = $this->getResult($userRepository,'u','COUNT(u.id) as count','u.gender','u.gender');
 $data =[
    'labels'=>["Male", "Female"],

     'data'=> [$results[0]['count'] , $results[1]['count'] ]];

        return new JsonResponse($data);
    }


    #[Route("/language", name:"app_language")]

    public function LangChart(StoryRepository $storyRepository): Response{

        $results = $this->getResult($storyRepository,'s','COUNT(s.id) as count','s.language','s.language');
        $countTable = array();
        $languageTable = array();

        foreach ($results as $result) {
            $countTable[] = $result['count'];
            $languageTable[] = $result['language'];
        }

        $data = array(
            'data' => $countTable,
            'labels' => $languageTable
        );

        return new JsonResponse($data);

    }
public function getResult($repository,$alias,$select1,$select2,$groupBy){
    $queryBuilder = $repository->createQueryBuilder($alias);
    $queryBuilder
        ->select($select1)
        ->addSelect($select2)
        ->groupBy($groupBy);

    return $queryBuilder->getQuery()->getResult();
}
}