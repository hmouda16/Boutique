<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function addProduct($id)
    {
        //recuperation du contenu du panier 
        $cart = $this->requestStack->getSession()->get('cart', []);

        if (!empty($cart[$id])) $cart[$id]++;
        else $cart[$id] = 1;

        return $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decreaseProduct($id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else unset($cart[$id]);


        return $this->requestStack->getSession()->set('cart', $cart);
    }


    public function getProduct()
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    public function deleteCart()
    {
        return $this->requestStack->getSession()->remove('cart', []);
    }

    public function removeProduct($id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

        unset($cart[$id]);

        return $this->requestStack->getSession()->set('cart', $cart);
    }



    public function getOrderProducts($repo)
    {
        $cart = $this->getProduct();

        $tabproducts = [];
        foreach ($cart as $id => $quantity) {
            $tabproducts[] = [
                'product' => $repo->findById($id),
                'quantity' => $quantity
            ];
        }
        return $tabproducts;
    }
}
