<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    #[Route('/lucky/number/{max}')]
    public function number($max = 100): Response
    {
        $number = random_int(0, $max);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
    
    #[Route('/lucky/hello')]
    public function hello(): Response
    {

        return new Response(
            '<html><body><h1>Hello world!</h1></body></html>'
        );
    }
    
     #[Route('/lucky/email')]
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
    
    
    
}