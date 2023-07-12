<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use SebastianBergmann\CodeCoverage\Report\Text;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{


    private $hashPassword;
    private $userRepo;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userrepo)
    {

        $this->hashPassword = $passwordHasher;
        $this->userRepo = $userrepo;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-file-alt'); //->setLabel(false);
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                $action
                    ->setIcon('fas fa-trash')
                    ->displayIf(static function ($entity) {

                        foreach ($entity->getRoles() as $role) {
                            return $role != 'ROLES_ADMIN';
                        }
                    });
                return $action;
            });
        //->remove('index', Action::NEW);
    }

    public function configureFilters(Filters $filters): Filters
    {

        if ($users = $this->userRepo->findAll()) {

            foreach ($users as $user) {
                $tabEmail[$user->getEmail()] = $user->getEmail();
            }
            return $filters
                ->add('firstName')
                ->add('lastName')
                ->add(ChoiceFilter::new('email')->setChoices($tabEmail))
                ->add(ArrayFilter::new('roles')->setChoices(['Admin' => 'ROLE_ADMIN', 'Utilisateur' => '']));
        } else {
            return $filters
                ->add('firstName')
                ->add('lastName');
        }
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
            BooleanField::new('active'),
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
