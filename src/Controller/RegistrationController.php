<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CompanyRegistrationDto;
use App\Form\CompanyRegistrationType;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly RegistrationService $registrationService,
        private readonly \Psr\Log\LoggerInterface $logger,
    ) {
    }

    #[Route('/rejestracja', name: 'app_register')]
    public function register(Request $request): Response
    {
        $registrationDto = new CompanyRegistrationDto();
        $form = $this->createForm(CompanyRegistrationType::class, $registrationDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Register the company with owner
                $this->registrationService->registerCompanyFromDto($registrationDto);

                // Add flash message
                $this->addFlash('success', 'Twoja rejestracja została przyjęta. Po weryfikacji przez administratora otrzymasz potwierdzenie na email.');

                // Redirect to confirmation page
                return $this->redirectToRoute('app_register_confirmation');
            } catch (\Throwable $e) {
                // Log the exception for debugging
                $this->logger->error('Registration error: '.$e->getMessage()."\n".$e->getTraceAsString(), ['exception' => $e]);
                $this->addFlash('danger', 'Wystąpił błąd podczas rejestracji. Prosimy spróbować ponownie później.');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/rejestracja/potwierdzenie', name: 'app_register_confirmation')]
    public function registerConfirmation(): Response
    {
        return $this->render('registration/confirmation.html.twig');
    }
}
