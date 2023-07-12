<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 11; $i++) {
            $categorie = new Category();

            $categorie->setName('cat' . $i);

            $manager->persist($categorie);

            //ajout d'une reference pour utilisation pour productFixtures
            $this->addReference('categorie_' . $i, $categorie);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
