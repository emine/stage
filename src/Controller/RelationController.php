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
use App\Entity\Relation;
use App\Entity\Demand;

class RelationController extends AbstractController
{
    #[Route('/relation/create/{id_demand}', name: 'create_relation')]
    public function create_relation(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, int $id_demand): Response {
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
            
            
            return $this->redirectToRoute('show_demand', ['id' => $id_demand]); 
            
        } else {
              throw $this->createNotFoundException(
                'Demande non trouvée :  id '.$id
            );
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
    
    
}
  