<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function addCategory(Request $request, EntityManagerInterface $em)
    {
        $category = new Category;
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('success');
        }


        return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/category/edit/{id}",name="editCategory")
     */
    public function editCategory(Request $request, EntityManagerInterface $em, $id)
    {

        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('success');
        }


        return $this->render('category/edit.html.twig', ['form' => $form->createView()]);
    }
}
