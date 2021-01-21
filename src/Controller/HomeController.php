<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    protected $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Lister tous les produit toute catégorie confondue
     * @Route("/",name="index")
     *  */
    public function index(EntityManagerInterface $em)
    {
        //Lister les produits
        $listeProduits = $em->getRepository(Product::class)->findAll();

        return $this->render('index.html.twig', ['listeProduits' => $listeProduits]);
    }



    /**
     * @Route("/produit/{id}-{slug}", name="vueProduit")
     */
    public function detailProduit(Product $product): Response
    {
        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/success",name="success")
     *  */
    public function success()
    {
        return $this->render('success.html.twig');
    }


    /**
     * @Route("/sendmail",name="sendMail")
     *  */
    public function sendMail()
    {
        $email = new Email();
        $email->from(new Address("noreply@test.com", "test"))
            ->to("yvon.huynh@gmail.com")
            ->text("Contenu du email de test")
            ->subject("Sujet du mail");

        $this->mailer->send($email);

        return new Response("Email envoyé");
    }

    /**
     * @Route("/sendmailtwig",name="sendMailTwig")
     *  */
    public function sendMailTwig()
    {
        $contenu = "Contenu email Twig";

        $email = new TemplatedEmail();
        $email->from(new Address("noreply@test.com", "test"))
            ->to("yvon.huynh@gmail.com")
            ->htmlTemplate("emails/emailTemplate.html.twig")
            ->context(['contenu' => $contenu])
            ->subject("Sujet du mail twig");

        $this->mailer->send($email);

        return new Response("Email twig envoyé");
    }
}
