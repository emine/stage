<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse ;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Relation;
use App\Entity\Demand;
use App\Entity\Message;
use App\Entity\User;


class RelationController extends AbstractController
{
    #[Route('/relation/create/{id_demand}', name: 'create_relation')]
    public function create_relation(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, Request $request, int $id_demand): Response {
        // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        // some security
        if ($doctrine->getRepository(Demand::class)->find($id_demand)) {
            $relation = new Relation ;
            $relation->setIdUser($this->getUser()->getId());
            $relation->setIdDemand($id_demand);
            
            $entityManager->persist($relation);
            $entityManager->flush();
            
            // TODO send mail
            
            // now create initial message
            $message = new Message ;
            $message->setIdRelation($relation) ;
            $message->setIdUser($this->getUser());
            $message->setMessage($request->request->get('message', ''));
            $message->setDateCreated(new \DateTime());
            
            $entityManager->persist($message);
            $entityManager->flush();
           
            return new JsonResponse(['success' => true]) ;
            
        } else {
            return new JsonResponse(['success' => false, message => "Demande Id non trouvée " . $id_demand]) ;
        }    
    }
    
    #[Route('/relation/relations', name: 'my_relations')]
    public function my_relations(ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response {
          // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        // relations faites
        $sentRelations = $doctrine->getRepository(Relation::class)->sentRelations($this->getUser());
        
        // relations recues
        $receivedRelations = $doctrine->getRepository(Relation::class)->receivedRelations($this->getUser());
        
        return $this->render('relation/my_relations.html.twig', [
                'sentRelations' => $sentRelations,
                'receivedRelations' => $receivedRelations,
        ]);
    }
    
    #[Route('/relation/conversation/{id_relation}', name: 'conversation')]
    public function conversation(ManagerRegistry $doctrine,  Request $request, EntityManagerInterface $entityManager, int $id_relation): Response {
        
         // scramble out if user is not connected 
        if ($this->getUser() === null) {
            return $this->redirectToRoute('site-home'); 
        }
        $relation = $doctrine->getRepository(Relation::class)->find($id_relation);
        
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $message = new Message ;
            $message->setIdRelation($relation) ;
            $message->setIdUser($this->getUser());
            $message->setMessage($request->request->get('message', ''));
            $message->setDateCreated(new \DateTime());
            
            $entityManager->persist($message);
            $entityManager->flush();
        }
        
    
        // I would have a much more succint code if I used foreign keys  
        $id_demand = $relation->getIdDemand() ;
        $demand = $doctrine->getRepository(Demand::class)->find($id_demand);
        // ah ah find the partner
        $id_user = $demand->getUserId() ;
        if ($id_user == $this->getUser()->getId()) {
            // then get conversation partner on relation
            $id_user = $relation->getIdUser() ;
        }
        $user = $doctrine->getRepository(User::class)->find($id_user);
 
        return $this->render('relation/conversation.html.twig', [
                'messages' =>  $doctrine->getRepository(Message::class)->getMessages($id_relation),
                'demand' => $demand ,
                'user' => $user,
        ]);

    }
    
    
    
}
  