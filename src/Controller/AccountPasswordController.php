<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{

    private $manager;
    private $hashPassword;


    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->hashPassword = $passwordHasher;
    }


    /**
     * @Route("/compte/modifier-mot-de-passe", name="app_account_password")
     */
    public function index(Request $request): Response
    {


        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if (!$this->hashPassword->isPasswordValid($user, $user->getOldPassword())) {
                $this->addFlash(
                    'danger',
                    'L\'ancien mot de passe est incorecte'
                );
            } else {
                $hashedPassword = $this->hashPassword->hashPassword(
                    $user,
                    $user->getNewPassword()
                );
                $user->setPassword($hashedPassword);



                $this->manager->persist($user);
                $this->manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été correctement mofifié'
                );

                return  $this->redirectToRoute('app_account');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
