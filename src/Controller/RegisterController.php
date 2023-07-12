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



    #[Route(path: '/inscription', name: 'register')]
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

            $user->setActive(0);



            $this->manager->persist($user);
            $this->manager->flush();

            $token = sha1($user->getEmail() . $user->getPassword());
            $content = $this->getParameter('DOMAINE') . 'inscription' . $user->getEmail() . '/' . $token;
            mail($user->getEmail(), 'activation du compte', $content);

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

    #[Route(path: '/inscription/{email}/{token}', name: 'activate')]
    public function activation(User $user, $token, EntityManagerInterface $manager): Response
    {
        if ($user->getEmail()) {
            $tokenVerif = sha1($user->getEmail() . $user->getPassword());

            if ($token == $tokenVerif) {
                $user->setActive(1);
            }
            $manager->flush();

            $this->addFlash(
                'success',
                'Compte activé avec succes'
            );
        } else {
            $this->addFlash(
                'danger',
                'Le lien est incorect'
            );
        }


        return $this->redirectToRoute('app_login');
    }
}
