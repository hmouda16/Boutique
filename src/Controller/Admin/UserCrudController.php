<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use SebastianBergmann\CodeCoverage\Report\Text;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{


    private $hashPassword;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {

        $this->hashPassword = $passwordHasher;
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }



    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setFormOptions([
                'validation_groups' => ['register'] //Contrainte de créationd'un compte(voir entité User)
            ]);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('lastName')->setLabel('Nom'),
            TextField::new('Pseudo')->setLabel('Pseudo'),
            EmailField::new('email')->setLabel('Email'),
            IntegerField::new('age')->setLabel('Age'),
            EmailField::new('confirmEmail')->setLabel('confirmEmail'),
            ChoiceField::new('roles')->setChoices(['Admin' => 'ROLE_ADMIN', 'Utilisateur' => 'ROLE_USER'])->allowMultipleChoices(),
            TextField::new('password')->setFormType(PasswordType::class)->onlyWhenCreating()->setLabel('Mot de passe'),
            TextField::new('confirmPassword')->setFormType(PasswordType::class)->onlyWhenCreating()->setLabel('confirmé mot de passe')->setRequired(true)
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $hashedPassword = $this->hashPassword->hashPassword(
            $entityInstance,
            $entityInstance->getPassword()
        );
        $entityInstance->setPassword($hashedPassword);

        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
