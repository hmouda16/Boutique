<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;





class RegisterController extends AbstractController
{
    private $manager;
    private $hashPassword;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->manager = $manager;
        $this->hashPassword = $passwordHasher;
    }



    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request): Response
    {

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $this->hashPassword->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);



            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur d\'email ' . $user->getEmail() . 'a été correctement enrengistré'
            );

            return  $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
