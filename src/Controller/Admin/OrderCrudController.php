<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions->remove('index', 'new')->remove('index', 'edit')->remove('index', 'delete');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt'),
            AssociationField::new('user'),
            MoneyField::new('total')->setCurrency('EUR'),
            BooleanField::new('statut', 'Payée'),
            TextField::new('carrier.name', 'Transporteur'),
            MoneyField::new('carrier.price', 'Frais de port')->setCurrency('EUR'),

        ];
    }
}
