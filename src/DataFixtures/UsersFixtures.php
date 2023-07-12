<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{



    private $hashPassword;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher)
    {

        $this->hashPassword = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        $admin = new User();
        $admin->setFirstName('Ahmed')
            ->setLastName('lab')
            ->setPseudo('admin')
            ->setEmail('test@test.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hashPassword->hashPassword(
                $admin,
                'password'
            ));
        $manager->persist($admin);
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i < 11; $i++) {


            $user = new User();
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPseudo($faker->firstName())
                ->setEmail($faker->email())
                ->setPassword($this->hashPassword->hashPassword(
                    $user,
                    'password'
                ));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
