<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Entity\Advertisement;
use App\Form\Backoffice\AdvertisementType;
use App\Repository\AdvertisementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/backoffice/advertisements', name: 'backoffice_advertisement_')]
class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        // Get the current logged-in employee
        $employee = $this->getUser();
        
        // Get the company associated with the employee
        $companies = $employee->getEmployedAt();
        $ownedCompanies = $employee->getOwnedCompanies();
        
        // Initialize advertisements array
        $advertisements = [];
        
        // Get advertisements from companies where the employee is employed
        foreach ($companies as $company) {
            $companyAds = $advertisementRepository->findByCompany($company);
            $advertisements = array_merge($advertisements, $companyAds);
        }
        
        // Get advertisements from companies owned by the employee
        foreach ($ownedCompanies as $company) {
            $companyAds = $advertisementRepository->findByCompany($company);
            $advertisements = array_merge($advertisements, $companyAds);
        }
        
        // Remove duplicates
        $uniqueAds = [];
        foreach ($advertisements as $ad) {
            $uniqueAds[$ad->getId()] = $ad;
        }
        
        return $this->render('backoffice/advertisement/index.html.twig', [
            'advertisements' => array_values($uniqueAds),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advertisement = new Advertisement();
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the current user as the creator
            $advertisement->setCreatedBy($this->getUser());
            
            // Get the company for this advertisement 
            // (If user belongs to multiple companies, use first one for simplicity)
            $employee = $this->getUser();
            $companies = [];
            
            // Check if the employee owns any companies
            if (!$employee->getOwnedCompanies()->isEmpty()) {
                $company = $employee->getOwnedCompanies()->first();
            } elseif (!$employee->getEmployedAt()->isEmpty()) {
                // Otherwise check employed at companies
                $company = $employee->getEmployedAt()->first();
            } else {
                // User has no company - cannot create advertisement
                $this->addFlash('error', 'Nie możesz utworzyć ogłoszenia, ponieważ nie jesteś przypisany do żadnej firmy.');
                return $this->redirectToRoute('backoffice_dashboard');
            }
            
            // Set the company for this advertisement
            $advertisement->setCompany($company);
            
            // Save to database
            $entityManager->persist($advertisement);
            $entityManager->flush();

            $this->addFlash('success', 'Ogłoszenie zostało pomyślnie utworzone.');
            return $this->redirectToRoute('backoffice_advertisement_index');
        }

        return $this->render('backoffice/advertisement/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[IsGranted('view', 'advertisement')]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render('backoffice/advertisement/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', 'advertisement')]
    public function edit(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdvertisementType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ogłoszenie zostało pomyślnie zaktualizowane.');
            return $this->redirectToRoute('backoffice_advertisement_index');
        }

        return $this->render('backoffice/advertisement/edit.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('delete', 'advertisement')]
    public function delete(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($advertisement);
            $entityManager->flush();
            $this->addFlash('success', 'Ogłoszenie zostało pomyślnie usunięte.');
        }

        return $this->redirectToRoute('backoffice_advertisement_index');
    }
} 