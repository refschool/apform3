<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function test(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->render(
            'test.html.twig',
            ['products' => $products]
        );
    }

    /**
     * @Route("/category",name="category")
     *  */
    public function category(CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find(1);

        return $this->render(
            'category.html.twig',
            ['products' => $category]
        );
    }
}
