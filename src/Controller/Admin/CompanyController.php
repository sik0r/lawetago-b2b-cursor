<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(
        private readonly CompanyRepository $companyRepository,
        private readonly CompanyService $companyService,
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

    public function show(int $id): Response
    {
        $company = $this->companyRepository->find($id);

        if (!$company) {
            throw new NotFoundHttpException('Firma o podanym ID nie istnieje.');
        }

        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * Activate a company.
     *
     * @param int $id The company ID
     */
    #[Route('/admin/companies/{id}/activate', name: 'admin_company_activate', methods: ['POST'])]
    public function activate(int $id): JsonResponse
    {
        $company = $this->companyRepository->find($id);

        if (!$company) {
            return new JsonResponse(['error' => 'Firma o podanym ID nie istnieje.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->companyService->activateCompany($company);

            return new JsonResponse([
                'success' => true,
                'message' => 'Firma została pomyślnie aktywowana.',
                'company' => [
                    'id' => $company->getId(),
                    'name' => $company->getName(),
                    'status' => $company->getStatus(),
                ],
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
