<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchFilters;
use App\Form\SearchFiltersType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route(path: '/nos-produits', name: 'products')]
    public function index(ProductRepository $repo, Request $request): Response
    {


        //$products = $repo->find(101);
        //$products = $repo->findBy(['id'=>101]);

        // on peut remplacer le X par n'importe quel nom de propriété
        //$products = $repo->findByX();


        //$products = $repo->findByName('quos necessitatibus eum');
        //$products = $repo->findBy(['category' => [124, 126]], ['price' => 'desc']);
        //$products = $repo->findAll();


        $error = null;


        $search = new SearchFilters();

        $form = $this->createForm(SearchFiltersType::class, $search);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            if (count($search->getCategories()) > 0) {
                foreach ($search->getCategories() as $categorie) {
                    $catId[] = $categorie->getId();
                }

                $products = $repo->findBy(['category' => $catId], ['price' => 'desc']);
                if (!$products) {
                    $error = "il n'y a pas de produit disponible dans les categorie selectionées";
                }
            } else {
                $products = $repo->findAll();
            }
        } else {
            $products = $repo->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
            'error' => $error
        ]);
    }



    #[Route(path: '/produit/{slug}', name: 'product')]
    public function show(Product $product)
    {
        //$product = $repo->findBySlug($slug);


        return $this->render('product/show.html.twig', [
            'product' => $product,


        ]);
    }
}
