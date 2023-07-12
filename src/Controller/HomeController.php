<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function index(RequestStack $requestStack, ProductRepository $repo): Response
    {






        // $panier = $requestStack->getSession()->get('cart', []);
        // $requestStack->getSession()->get('cart');

        // $panier[301] = 3;
        // dump($panier);




        // $panier[401] = 1;
        // $requestStack->getSession()->set('cart', $panier);
        // dd($requestStack->getSession()->get('cart'));








        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
