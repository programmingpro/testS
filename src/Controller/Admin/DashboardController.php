<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\News;
use App\Entity\Source;
use App\Repository\SourceRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private SourceRepository $sourceRepository // Используем новый синтаксис конструктора
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/layout.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project');
    }

    public function configureMenuItems(): iterable
    {
        return  [
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('News', 'fa fa-file-text', News::class),
            MenuItem::linkToCrud('Sources', 'fa fa-file-text', Source::class),
        ];
    }
}
