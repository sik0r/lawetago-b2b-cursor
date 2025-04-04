<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('backoffice/dashboard/index.html.twig');
    }
} 