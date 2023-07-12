<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\OrderRepository;
use App\Controller\Admin\OrderCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{


    private $repoOrder;

    public function __construct(OrderRepository $repoOrder)
    {
        $this->repoOrder = $repoOrder;
    }

    #[Route(path: '/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        //return $this->render('admin/dashBoard.html.twig', []);

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Option 1. Make your dashboard redirect to the same page for all users
        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyBotique');
    }

    public function configureMenuItems(): iterable
    {

        $nbrsOrderWait = count($this->repoOrder->findByStatut(0));
        $nbrsOrderPaid = count($this->repoOrder->findByStatut(1));

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes <span class ="badge badge-success">' . $nbrsOrderPaid . '</span><span class ="badge badge-danger">' . $nbrsOrderWait . '</span>', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::section('Produits');
        yield MenuItem::linkToCrud('Categorie', 'fas fa-folder-plus', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-shipping-fast', Carrier::class);
    }
}
