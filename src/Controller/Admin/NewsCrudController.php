<?php

namespace App\Controller\Admin;

use App\Entity\News;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class NewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return News::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            AssociationField::new('category')
                ->setLabel('Category Name')
                ->formatValue(fn ($value, News  $entity) => $entity->getCategory()->getName()),
            AssociationField::new('source')
                ->setLabel('Source')
                ->formatValue(fn ($value, News $entity) => $entity->getSource()->getName()),
            DateField::new('pubDate'),
            TextField::new('link'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('source', 'Источник'))
            ->add(EntityFilter::new('category', 'Категория'))
            ->add(DateTimeFilter::new('pubDate', 'Дата публикации'));
    }
}
