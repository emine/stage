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
    
    #[Route('/testplainform', name: 'test_plain_form')]
    public function testPlainHtmlForm(ManagerRegistry $doctrine, Request $request): Response {
        
        $submit = $request->request->get('submit', false); 
        if ($submit) {
            $age = $request->request->get('age', '');
            return new Response('Age is ' . $age);
        }
        
         return $this->render('test/test.html.twig', [

        ]);
        
    }
    
    
}