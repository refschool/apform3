<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/admin",name="admin_")
 */
class ProductController extends AbstractController
{


    /**
     * liste le produits d'une catégorie
     * @Route("/product/detail/{id}", name="detailProduit")
     */
    public function categoryProductList(Product $product): Response
    {
        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }



    /**
     * @Route("/product/add",name="ajoutProduit")
     */
    public function addProduct(KernelInterface $appKernel, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        //$path = $appKernel->getProjectDir() . '/public/img';

        $path = $this->getParameter('app.dir.public') . '/img';

        $product = new Product;
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setSlug($slugger->slug($product->getName()));

            $file = $form['img']->getData();
            $idCategory = $form['category']->getData()->getId();
            //dd($idCategory);


            if ($file) {
                // récup nom de fichier sans extension
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
                // set nom dans la propriété Img
                $product->setImg($newFilename);

                //Déplacer le fichier dans le répertoire public + sous répertoire
                try {
                    $file->move($path, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();
                }
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            return $this->redirectToRoute('categoryProduct', ['id' => $idCategory]);
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
        $path = $this->getParameter('app.dir.public') . '/img';
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $file = $form['img']->getData();
            if ($file) {
                // récup nom de fichier sans extension
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
                // set nom dans la propriété Img
                $product->setImg($newFilename);

                //Déplacer le fichier dans le répertoire public + sous répertoire
                try {
                    $file->move($path, $newFilename);
                } catch (FileException $e) {
                    echo $e->getMessage();
                }
            }



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
    public function deleteProduct(Product $product, EntityManagerInterface $em)
    {
        // public function deleteProduct(ProductRepository $productRepository, $id, EntityManagerInterface $em)

        //$product = $productRepository->find($id);
        $idCategory = $product->getCategory()->getId(); //comment trouver l'id category ?


        // paramConverter
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Produit effacé avec succès');

        return $this->redirectToRoute('categoryProduct', ['id' => $idCategory]);
    }



    /**
     * liste le produits d'une catégorie
     * @Route("/product/testDQL/{id}", name="testDQL")
     */
    public function testDQL(Product $product, EntityManagerInterface $em, $id): Response
    {
        // DQL
        $query = $em->createQuery(
            "
            select p 
            FROM App\Entity\Product p 
            WHERE p.id =  :id
            "
        )->setParameter('id', $id);

        $data = $query->getResult();
        dd($data);

        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * liste le produits d'une catégorie
     * @Route("/product/testDQLJoin/{id}", name="testDQLJoin")
     */
    public function testDQLJoin(Product $product, EntityManagerInterface $em, $id): Response
    {
        // DQL
        $query = $em->createQuery(
            "
            select c,p 
            FROM App\Entity\Category c 
            JOIN c.products p
            WHERE c.id =  :id
            "
        )->setParameter('id', $id);

        $data = $query->getResult();
        dd($data);

        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }
}
