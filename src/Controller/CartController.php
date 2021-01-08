<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        //Afficher le contenu du panier
        $cart  = $session->get('cart', []);

        // Reformater la liste des produits du panier
        $cart_view = array_count_values($cart);
        //dd($cart_view);
        // Boucle pour récupérer les produits à partir des ids
        foreach ($cart_view as $key => $value) {
            $newCart[] = [
                'produit' => $productRepository->find($key),
                'quantite' => $value
            ];
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $newCart,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="addToCart")
     */
    public function add(Request $request, SessionInterface $session, $id): Response
    {

        $cart = $session->get('cart', []);

        array_push($cart, $id);
        $session->set('cart', $cart);

        // $session->remove('cart');


        $this->addFlash('success', 'Produit ajouté avec succès');

        return $this->redirectToRoute('cart');
    }
}
