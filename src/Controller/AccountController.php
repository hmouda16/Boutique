<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route(path: '/compte', name: 'app_account')]
    public function index(): Response
    {





        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }


    #[Route(path: '/compte/mes-commandes', name: 'account_order')]
    public function commandes(OrderRepository $repo): Response
    {





        return $this->render('account/order.html.twig', [
            'orders' => $repo->findOrderUser($this->getUser())
        ]);
    }



    #[Route(path: '/compte/commandes/{reference}', name: 'account_order_show')]
    public function show(Order $order): Response
    {


        if ($order->getUser() != $this->getUser()) return $this->redirectToRoute('account_order');

        return $this->render('account/show.html.twig', [
            'order' => $order
        ]);
    }
}
