<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,
    ) {
    }

    public function index(Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 10;

        $companies = $this->companyRepository->findPaginated($page, $limit);
        $totalCompanies = $this->companyRepository->count([]);
        $totalPages = ceil($totalCompanies / $limit);

        return $this->render('admin/company/index.html.twig', [
            'companies' => $companies,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCompanies' => $totalCompanies,
        ]);
    }
} 