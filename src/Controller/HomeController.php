<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

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
     * @Route("/",name="index")
     *  */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/test",name="test")
     *  */
    public function test(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render(
            'test.html.twig',
            ['products' => $products]
        );
    }

    /**
     * @Route("/success",name="success")
     *  */
    public function success()
    {
        return $this->render('success.html.twig');
    }
}
