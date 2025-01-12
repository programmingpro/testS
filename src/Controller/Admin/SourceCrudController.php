<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Entity\Source;
use App\Repository\SourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TemplateField;

class SourceCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Source::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('url'),
        ];

        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = IntegerField::new('newsCount', 'Количество новостей')
                ->formatValue(function ($value, Source $entity) {
                    /** @var SourceRepository $sourceRep */
                    $sourceRep = $this->entityManager->getRepository(Source::class);
                    return $sourceRep->countNewsBySourceAndPeriod($entity);
                });
        }

        return $fields;
    }
}