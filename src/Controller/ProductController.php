<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/detailProduit/{id}", name="detailProduit")
     */
    public function index(ProductRepository $productRepository): Response
    {

        $listeProduit = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'listeProduit' => $listeProduit,
        ]);
    }



    /**
     * @Route("/product/add",name="ajoutProduit")
     */
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $builder = $this->createFormBuilder();
        $builder->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('slug', TextType::class)
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'description',
                    'placeholder' => 'Choisir une catégorie',
                    'label' => 'Catégorie',
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'Ajouter Produit']
            );

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product = new Product;
            $product->setName($data['name'])
                ->setPrice($data['price'])
                ->setSlug($data['slug'])
                ->setCategory($data['category']);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/product/edit/{id}",name="editProduit")
     */
    public function editProduct(Request $request, EntityManagerInterface $em, $id): Response
    {

        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}",name="deleteProduit")
     */
    public function deleteProduct(Product $product, $id, EntityManagerInterface $em)
    {
        // public function deleteProduct(ProductRepository $productRepository, $id, EntityManagerInterface $em)

        //$product = $productRepository->find($id);
        // paramConverter
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('success');
    }
}
