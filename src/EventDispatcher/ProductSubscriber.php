<?php
// Event 3ème étape : création de la classe Subscriber (Event Listener)
namespace App\EventDispatcher;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Event\ProductAddSuccessEvent;
use App\Event\ProductDeleteSuccessEvent;
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
            'productAdd.success' => 'sendAddProductMail',
            'productDelete.success' => 'sendDeleteProductMail',
        ];
    }

    public function sendAddProductMail(ProductAddSuccessEvent $e)
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


    public function sendDeleteProductMail(ProductDeleteSuccessEvent $e)
    {
        $product = $e->getProduct();

        $email = new TemplatedEmail();
        $email->from(new Address("noreply@test.com", "test"))
            ->to("yvon.huynh@gmail.com")
            ->htmlTemplate("emails/emailTemplate.html.twig")
            ->context(['product' => $product])
            ->subject("Un produit vient d'être effacé");

        $this->mailer->send($email);
    }
}
