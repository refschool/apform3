<?php
// Event 3ème étape : création de la classe Subscriber (Event Listener)
namespace App\EventDispatcher;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Event\ProductAddSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'product.success' => 'sendMail',
        ];
    }

    public function sendMail(ProductAddSuccessEvent $e)
    {
        $product = $e->getProduct();
        //envoi email avec les information produits
        // $email = new Email();
        // $email->from(new Address("noreply@test.com", "test"))
        //     ->to("yvon.huynh@gmail.com")
        //     ->text("Un produit vient d etre ajouté")
        //     ->subject("Ajout de produit");

        // $this->mailer->send($email);


        $email = new TemplatedEmail();
        $email->from(new Address("noreply@test.com", "test"))
            ->to("yvon.huynh@gmail.com")
            ->htmlTemplate("emails/emailTemplate.html.twig")
            ->context(['product' => $product])
            ->subject("Un nouveau produit vient d'être ajouté");

        $this->mailer->send($email);
    }
}
