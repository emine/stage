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

use Symfony\Component\Asset\UrlPackage;
use App\Entity\User;
use App\Entity\Orders;
use App\Entity\OrderLines;


use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TestController extends AbstractController
{
    #[Route('/test/number/{max}')]
    public function number($max = 100): Response
    {
        $number = random_int(0, $max);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
    
    #[Route('/test/hello')]
    public function hello(): Response
    {

        return new Response(
            '<html><body><h1>Hello world!</h1></body></html>'
        );
    }
    
   
    
     #[Route('/test/email')]
    public function email(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('emile@ejerome.net')
            ->to('olivia@ejerome.net')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        return new Response(
            '<html><body>Mail sent</body></html>'
        );
        // ...
    }
    
    #[Route('/test_user', name: 'test_user')]
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
    
    #[Route('/test_plain_form', name: 'test_plain_form')]
    public function testPlainHtmlForm(ManagerRegistry $doctrine, Request $request): Response {
        
        $age = '' ;
        $photo= null ;
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $age = $request->request->get('age', '');
            // manage photo
            // https://symfonycasts.com/screencast/symfony-uploads/
            $photo = $request->files->get('photo') ;
            $filename = $photo->getClientOriginalName();
            $newFilename = uniqid().'.'.$photo->guessExtension();
            
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

            $photo->move(
                $destination,
                $newFilename
            );
           return new Response('File ' . $newFilename . ' saved in /public/uploads');
            
        }
        
         return $this->render('test/test.html.twig', [
             'age' => $age,
             'photo' => $photo
        ]);
        
    }
    
    #[Route('/test_orders', name: 'test_orders')]
    public function testOrders(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $o = new Orders();
        $o->setName('Tonton');
        
        // tell Doctrine you want to (eventually) save  (no queries yet)
        $entityManager->persist($o);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        $l = new OrderLines ;
        $l->setDescription('camember') ;
        $l->setPrice(53) ;
        $l->setOrders($o) ;
        $o->addOrderLine($l) ;
        $entityManager->persist($l);
        $entityManager->flush();
        
        $l = new OrderLines ;
        $l->setDescription('boursin') ;
        $l->setPrice(34) ;
        $l->setOrders($o) ;
        $o->addOrderLine($l) ;
        $entityManager->persist($l);
        $entityManager->flush();
        
        $l = new OrderLines ;
        $l->setDescription('EMENTAL') ;
        $l->setPrice(523) ;
        $l->setOrders($o) ;
        $o->addOrderLine($l) ;
        $entityManager->persist($l);
        $entityManager->flush();
        
        $out = '' ;
        $col = $o->getOrderLines() ;
        foreach ($col->getValues() as $line) {
            $out .= $line->getDescription() . ' ' . $line->getPrice() . '<br>' ; 
        }

        return new Response($out) ;
    }
    
    #[Route('/test_form', name: 'test_form')]
    public function testForm(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $o = new Orders();
        $o->setName('Tonton');

        $form = $this->createFormBuilder($o)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();
        
        return $this->renderForm('test/test_form.html.twig', [
            'form' => $form,
        ]);

        // ...
    }
    
    
    
}