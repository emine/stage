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
use App\Entity\Relation;
use App\Entity\User;



class DemandController extends AbstractController
{
   /*
     * id = 0 => add demand
     * id > 0 => edit demand id
     */
    #[Route('/demand/edit/{id}', name: 'edit_demand')]
    public function edit(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager, int $id): Response {
        
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        $isNew = $id == 0 ;
        if ($isNew) {
            $demand = new Demand ;
        } else {
            $demand = $doctrine->getRepository(Demand::class)->find($id);
        }    
        if (!$demand) {
            throw $this->createNotFoundException(
                'No demand found for id '.$id
            );
        }
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $demand->setTitle($request->request->get('title', ''));
            $demand->setText($request->request->get('text', ''));

            $demand->setUserId($this->getUser()->getId());
            // manage photo
            // https://symfonycasts.com/screencast/symfony-uploads/
            $photo = $request->files->get('photo') ;
            $filename = $photo->getClientOriginalName();
            $newFilename = uniqid().'.'.$photo->guessExtension();
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
            $photo->move($destination, $newFilename);
            $demand->setPhoto($newFilename) ;
            
            $entityManager->persist($demand);
            $entityManager->flush();
            
            return $this->redirectToRoute('show_demand', ['id' => $demand->getId()]); 
        }
        
        return $this->render('demand/demand.html.twig', [
            'title' => $isNew ? '' : $demand->getTitle() ,
            'text' => $isNew ? '' : $demand->getText() ,
            'id' => $id ,
        ]);
        
    }
    
    #[Route('/my_demands', name: 'my_demands')]
    public function myDemands(ManagerRegistry $doctrine): Response
    {
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        
        return $this->render('demand/my_demands.html.twig', [
            'demands' => $doctrine->getRepository(Demand::class)->getMyDemands($this->getUser()),
        ]);
    }
    
    #[Route('/all_demands', name: 'all_demands')]
    public function allDemands(ManagerRegistry $doctrine): Response
    {
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }

        return $this->render('demand/all_demands.html.twig', [
            'demands' => $doctrine->getRepository(Demand::class)->getAllDemands($this->getUser()),
        ]);
    }
    
    #[Route('/demand/show/{id}', name: 'show_demand')]
    public function show(ManagerRegistry $doctrine, int $id): Response {
        
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        $demand = $doctrine->getRepository(Demand::class)->find($id);
        $user = $doctrine->getRepository(User::class)->find($demand->getUserId());
        $relations = $doctrine->getRepository(Relation::class)->getAllRelations($demand);
        // attach user to relations
        // TOCONSIDER :I would not have to do this if I used foreign key !!!
        $fullRelations = [] ;
        $isRelated = false ;
        foreach ($relations as $relation) {
            $u = $doctrine->getRepository(User::class)->find($relation->getIdUser());
            $relation->setUser($u) ; 
            $fullRelations[] = $relation ;
            if ($relation->getIdUser() == $this->getUser()->getId()) {
                $isRelated = true ;
            }
        }
        if ($demand) {
            return $this->render('demand/show_demand.html.twig', [
                'demand' => $demand,
                'user' => $user,
                'relations' => $fullRelations,
                'isRelated' => $isRelated,
            ]);
        } else {
            throw $this->createNotFoundException(
                'No demand found for id '.$id
            );
        }
        
    }
        
    
}