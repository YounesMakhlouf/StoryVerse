<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\StoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RechercheController extends AbstractController
{
#[Route('/search', name: 'app_search')]
public function search(Request $request, UserRepository $userRepository, StoryRepository $storyRepository, EntityManagerInterface $entityManager): Response
{
$searchQuery = $request->query->get('searchQuery');


// Recherche des utilisateurs dont le nom d'utilisateur contient la chaîne de recherche
$users = $entityManager->createQueryBuilder()
->select('u')
->from(User::class, 'u')
->where('u.username LIKE :searchQuery ')
->setParameter('searchQuery', '%' . $searchQuery . '%')
->getQuery()
->getResult();

// Recherche de toutes les histoires dont le titre contient la chaîne de recherche
$stories = $storyRepository->createQueryBuilder('s')
->where('s.title LIKE :searchQuery')
->setParameter('searchQuery', '%' . $searchQuery . '%')
->getQuery()
->getResult();

// Rendre la vue Twig pour afficher les résultats de la recherche
return $this->render('recherche/recherche.html.twig', [
'users' => $users,
'stories' => $stories,
'searchQuery' => $searchQuery
]);
}
}
