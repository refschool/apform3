<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/",name="index")
     *  */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/test",name="test")
     *  */
    public function test()
    {
        $tab = ["toto\n\n\n",  "tata\n"];

        return $this->render(
            'test.html.twig',
            ['tab' => $tab]
        );
    }
}
