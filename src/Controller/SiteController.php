<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;

class SiteController extends AbstractController
{
    #[Route('/', name: 'site-home')]
    public function index(): Response
    {
        $user = $this->getUser(); 
        return $this->render('site/index.html.twig', [
            'user' => $user,
            'error' => $user ? null : 'Not connected'
        ]);
    }
    


    
    #[Route('/testuser', name: 'test_user')]
    public function testUser(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $user = new User();
        $user->setPseudo('Tonton');
        $user->setEmail('olivia@ejerome.net');
        $user->setPassword('yo');

        // tell Doctrine you want to (eventually) save the User (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new user with id '.$user->getId());
    }
    
    
}
