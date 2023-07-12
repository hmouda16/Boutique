<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart, ProductRepository $repo): Response
    {

        $tabproducts = $cart->getOrderProducts($repo);

        $cart = $cart->getProduct();

        return $this->render('cart/index.html.twig', [
            'products' => $tabproducts
        ]);
    }


    #[Route('/cart/add/{id}', name: 'add_cart')]
    public function add($id, Cart $cart): Response
    {


        $cart->addProduct($id);


        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease')]
    public function decrease($id, Cart $cart): Response
    {


        $cart->decreaseProduct($id);


        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'remove')]
    public function remove($id, Cart $cart): Response
    {


        $cart->removeProduct($id);


        return $this->redirectToRoute('cart');
    }


    #[Route('/cart/delete/', name: 'delete')]
    public function delete(Cart $cart): Response
    {


        $cart->deleteCart();


        return $this->redirectToRoute('cart');
    }
}
