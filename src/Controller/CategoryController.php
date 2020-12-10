<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {

        $listeCategory = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
    }



    /**
     * @Route("/category/add",name="ajoutCategory")
     */
    public function addCategory()
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category;
        $category->setName('Xiaomi')->setDescription('Un autre fabricant de smartphone')
            ->setSlug('xiaomi');

        $em->persist($category);

        $em->flush();
        //dd('fait');

        return $this->render('success.html.twig', []);
    }
}
