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
       
        return $this->render('site/index.html.twig', [
            'user' => $this->getUser(),
            'registerUrl' => $this->generateUrl('app_register') ,
            'loginUrl' => $this->generateUrl('app_login') ,
        ]);
    }
    
}
