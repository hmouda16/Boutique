<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Entity\Order;
use App\Services\Cart;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManager;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/compte/commande', name: 'order')]
    public function index(Request $request, Cart $cart, ProductRepository $repo, EntityManagerInterface $manager): Response
    {


        $tabProducts = $cart->getOrderProducts($repo);
        // dd($this->getUser()->getAdresses()->getValues());

        if (count($this->getUser()->getAdresses()->getValues()) == 0) {
            return $this->redirectToRoute('add_address');
        }


        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {


            // dd($form->get('adresses')->getData());
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new DateTime());
            $order->setDelivery($form->get('adresses')->getData()); //recuperation donnés sans entité
            $order->setCarrier($form->get('transporteurs')->getData()); //recuperation donnés sans entité
            $order->setStatut(0);

            $date = new DateTime();
            $date = $date->format('dmY');
            $order->setReference($date . '-' . uniqid());



            $manager->persist(($order));
            // $manager->flush();


            foreach ($tabProducts as $product) {

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product'][0]);
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product'][0]->getPrice());

                $manager->persist(($orderDetails));


                $stripeProducts[] =  [

                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $product['product'][0]->getName(),
                            'images' => [
                                $this->getParameter('DOMAINE') . '/uploads/images/' . $product['product'][0]->getIllustration()
                            ]
                        ],
                        'unit_amount' => round($product['product'][0]->getPrice())
                    ],
                    'quantity' => $product['quantity']

                ];
            }



            //ajout transporteur comme un produit
            $stripeProducts[] =  [

                'price_data' => [
                    'currency' => 'EUR',
                    'product_data' => [
                        'name' => $order->getCarrier()->getName(),
                    ],
                    'unit_amount' => round($order->getCarrier()->getPrice())
                ],
                'quantity' => 1

            ];







            Stripe::setApiKey($this->getParameter('STRIPE_SECRET_KEY'));

            $YOUR_DOMAIN = $this->getParameter('DOMAINE');

            $checkout_session = Session::create([
                'line_items' => $stripeProducts,
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . 'commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . 'commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            // dd($checkout_session);

            $order->setStripeSessionId($checkout_session->id);

            $manager->flush();

            // dd('ok');

            return $this->render('order/recap.html.twig', [
                'tabProducts' => $tabProducts,
                'order' => $order,
                'checkoutSession' => $checkout_session->url

            ]);
        }


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'tabProducts' => $tabProducts

        ]);
    }
}
