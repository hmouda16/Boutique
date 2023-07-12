<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Services\Cart;
use App\Form\AddressType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    #[Route('/compte/addresses', name: 'account_address')]
    public function index(AdressRepository $repo): Response
    {

        return $this->render('account/address.html.twig', [
            'adresses' => $repo->findByUser($this->getUser())
        ]);
    }

    #[Route('/compte/supprimer-une-adresse{id}', name: 'delete_address')]
    public function delete(EntityManagerInterface $manager, Adress $adress): Response
    {


        if ($adress->getUser() == $this->getUser()) {
            $manager->remove($adress);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'adresse ' . $adress->getname() . 'a été correctement supprimée.'
            );
        } else {
            $this->addFlash(
                'success',
                'L\'adresse ne vous appartient pas.'
            );
        }



        return $this->redirectToRoute('account_address');
    }



    #[Route('/compte/modifier-une-adresse{id}', name: 'edit_address')]
    public function edit(Request $request, EntityManagerInterface $manager, Adress $adress): Response
    {


        if ($adress->getUser() != $this->getUser()) {

            $this->addFlash(
                'success',
                'L\'adresse ne vous appartient pas .'
            );
            return $this->redirectToRoute('account_address');
        }



        $form = $this->createForm(AddressType::class, $adress);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {


            $manager->persist($adress);
            $manager->flush();


            $this->addFlash(
                'success',
                'L\'adresse ' . $adress->getName() . 'a été correctement modifiée.'
            );

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/add_address.html.twig', [
            'form' => $form->createView(),

        ]);
    }





    #[Route('/compte/ajouter-une-adresse', name: 'add_address')]
    public function add(Cart $cart, Request $request, EntityManagerInterface $manager): Response
    {


        $adresse = new Adress();

        $form = $this->createForm(AddressType::class, $adresse);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresse->setUser($this->getUser());
            $manager->persist($adresse);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'adresse de  ' . $adresse->getName() . 'a été correctement enrengistré'
            );

            if ($cart->getProduct()) {
                return $this->redirectToRoute('order');
            }
            return  $this->redirectToRoute('account_address');
        }


        return $this->render('account/add_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
