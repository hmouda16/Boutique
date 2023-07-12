<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success')]
    public function index(Order $order, $stripeSessionId, EntityManagerInterface $manager, Cart $cart): Response
    {

        if ($this->getUser() != $order->getUser()) {
            return $this->redirectToRoute('home');
        }


        $stripe = new StripeClient('sk_test_51NOJLMFNo1mf1XIgzgU4WC465TsEOYwTPKiE1PUP0ryhq2MA72r8cefd5l3T1aCk4ughoTB8vQcwkJgG7rRBAdod00SpnpkxhE');
        $session = $stripe->checkout->sessions->retrieve($stripeSessionId);

        if ($session->payment_status = !"paid") {
            return $this->redirectToRoute('ordrer_cancel', ['stripeSessionId' => $stripeSessionId]);
        }

        //on passe le statut de la commande a 1
        $order->setStatut(1);
        $manager->flush();
        $cart->deleteCart();

        $messageEmail = 'Votre commande N°: 123456789 à bien était valider, nous allons traiter votre commande dans les plus bref délais';
        mail($this->getUser()->getEmail(), 'Validation de votre commande', $messageEmail);




        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
