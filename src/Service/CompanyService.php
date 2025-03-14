<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CompanyService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MailerInterface $mailer,
    ) {
    }

    /**
     * Activate a company.
     *
     * @param Company $company The company to activate
     *
     * @return Company The activated company
     */
    public function activateCompany(Company $company): Company
    {
        // Only activate if the company is pending
        if (Company::STATUS_PENDING !== $company->getStatus()) {
            throw new \InvalidArgumentException('Tylko firmy oczekujące na weryfikację mogą zostać aktywowane.');
        }

        // Update company status
        $company->setStatus(Company::STATUS_ACTIVE);
        $company->setVerifiedAt(new \DateTimeImmutable());

        // Save changes
        $this->entityManager->flush();

        // Send notification email to company owner
        $this->sendActivationEmail($company);

        return $company;
    }

    /**
     * Send activation email to the company owner.
     */
    private function sendActivationEmail(Company $company): void
    {
        $owner = $company->getOwner();

        if (!$owner) {
            return;
        }

        $email = (new Email())
            ->from('noreply@lawetago.pl')
            ->to($owner->getEmail())
            ->subject('LawetaGO - Twoja firma została aktywowana')
            ->html($this->renderActivationEmailTemplate($company));

        $this->mailer->send($email);
    }

    /**
     * Render activation email template.
     */
    private function renderActivationEmailTemplate(Company $company): string
    {
        $owner = $company->getOwner();
        $ownerName = $owner ? $owner->getFullName() : '';

        return "
            <h2>Twoja firma została aktywowana w systemie LawetaGO!</h2>
            <p>Witaj {$ownerName},</p>
            <p>Twoja firma <strong>{$company->getName()}</strong> została pomyślnie zweryfikowana i aktywowana w systemie LawetaGO.</p>
            <p>Możesz teraz w pełni korzystać z platformy i oferować swoje usługi klientom.</p>
            <p>Pozdrawiamy,<br>Zespół LawetaGO</p>
        ";
    }
}
