<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Demand;

class DemandController extends AbstractController
{
    #[Route('/demand/add', name: 'demand_add')]
    public function add(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response {
        
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login'); 
        }
        
        $demand = new Demand ;
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $demand->setTitle($request->request->get('title', ''));
            $demand->setText($request->request->get('text', ''));
            $demand->setUserId($this->getUser()->getId());
            
            $entityManager->persist($demand);
            $entityManager->flush();
            
            return new Response('Demand saved OK');
        }
        
        return $this->render('demand/demand.html.twig', [
            'title' => '' ,
            'text' => '' ,
            'add' => true ,
        ]);
        
    }

    #[Route('/demand/edit/{id}', name: 'demand_edit')]
    public function edit(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager, int $id): Response {
        
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('app_login'); 
        }
    
        $demand = $doctrine->getRepository(Demand::class)->find($id);
        if (!$demand) {
              throw $this->createNotFoundException(
                'No demand found for id '.$id
            );
        }
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $demand->setTitle($request->request->get('title', ''));
            $demand->setText($request->request->get('text', ''));
            
            $entityManager->persist($demand);
            $entityManager->flush();
            
            return new Response('Demand saved OK');
        }
        
        return $this->render('demand/demand.html.twig', [
            'title' => $demand->getTitle() ,
            'text' => $demand->getText() ,
            'add' => false ,
            'id' => $id ,
        ]);
        
    }
    
    
}