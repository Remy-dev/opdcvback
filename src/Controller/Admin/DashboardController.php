<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Admin;
use App\Entity\Comment;
use App\Entity\Itinerary;
use App\Entity\Message;
use App\Entity\Product;
use App\Entity\Role;
use App\Entity\Tag;
use App\Entity\User;
use Cron\CronBundle\Entity\CronJob;
use Cron\CronBundle\Entity\CronReport;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="easyadmin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Back O prè de chez vous');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('O pre de chez vous Dashboard', 'fa fa-home');
        yield MenuItem::section('Entities', 'fas fa-dice-d20');
        yield MenuItem::linkToCrud('Admins', 'fas fa-user', Admin::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Addresses', 'fas fa-map-marker-alt', Address::class);
        yield MenuItem::linkToCrud('Itineraries', 'fas fa-map-marked-alt', Itinerary::class );
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Messages', 'far fa-comment-dots', Message::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Roles', 'fas fa-capsules', Role::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Cron jobs', 'fas fa-calendar', CronJob::class);
        yield MenuItem::linkToCrud('Cron reports', 'fas fa-folder', CronReport::class);
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
