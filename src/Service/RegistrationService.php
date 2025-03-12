<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CompanyRegistrationDto;
use App\Entity\Company;
use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly MailerInterface $mailer,
        private readonly string $adminEmail,
    ) {
    }

    /**
     * Register a new company with its owner from DTO.
     *
     * @param CompanyRegistrationDto $dto The registration data
     *
     * @return Company The registered company
     */
    public function registerCompanyFromDto(CompanyRegistrationDto $dto): Company
    {
        // Create company entity from DTO
        $company = new Company();
        $company->setName($dto->name);
        $company->setAddress($dto->address);
        $company->setNip($dto->nip);
        $company->setPhone($dto->phone);
        $company->setEmail($dto->email);
        $company->setStatus(Company::STATUS_PENDING);

        // Create the owner employee
        $employee = new Employee();
        $employee->setEmail($dto->email);
        $employee->setFirstName($dto->ownerFirstName);
        $employee->setLastName($dto->ownerLastName);
        $employee->setPhone($dto->phone);
        $employee->addRole(Employee::ROLE_COMPANY_OWNER);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword(
            $employee,
            is_array($dto->plainPassword) ? $dto->plainPassword['first'] : $dto->plainPassword
        );
        $employee->setPassword($hashedPassword);

        // Set the owner of the company
        $company->setOwner($employee);

        // Save to database
        $this->entityManager->persist($employee);
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Send confirmation email to user
        $this->sendUserConfirmationEmail($company, $employee);

        // Send notification to admin
        $this->sendAdminNotificationEmail($company, $employee);

        return $company;
    }

    /**
     * Send confirmation email to the user.
     */
    private function sendUserConfirmationEmail(Company $company, Employee $employee): void
    {
        $email = (new Email())
            ->from('noreply@lawetago.pl')
            ->to($employee->getEmail())
            ->subject('LawetaGO - Potwierdzenie rejestracji')
            ->html($this->renderUserEmailTemplate($company, $employee));

        $this->mailer->send($email);
    }

    /**
     * Send notification to the administrator.
     */
    private function sendAdminNotificationEmail(Company $company, Employee $employee): void
    {
        $email = (new Email())
            ->from('noreply@lawetago.pl')
            ->to($this->adminEmail)
            ->subject('LawetaGO - Nowa rejestracja firmy')
            ->html($this->renderAdminEmailTemplate($company, $employee));

        $this->mailer->send($email);
    }

    /**
     * Render user email template.
     */
    private function renderUserEmailTemplate(Company $company, Employee $employee): string
    {
        return "
            <h2>Dziękujemy za rejestrację w systemie LawetaGO!</h2>
            <p>Witaj {$employee->getFullName()},</p>
            <p>Twoja firma <strong>{$company->getName()}</strong> została zarejestrowana w systemie LawetaGO.</p>
            <p>Po weryfikacji przez administratora otrzymasz informację o aktywacji konta.</p>
            <p>Pozdrawiamy,<br>Zespół LawetaGO</p>
        ";
    }

    /**
     * Render admin email template.
     */
    private function renderAdminEmailTemplate(Company $company, Employee $employee): string
    {
        return "
            <h2>Nowa rejestracja firmy w systemie LawetaGO</h2>
            <p>Firma <strong>{$company->getName()}</strong> zarejestrowała się w systemie.</p>
            <p>Dane firmy:</p>
            <ul>
                <li>Nazwa: {$company->getName()}</li>
                <li>Adres: {$company->getAddress()}</li>
                <li>NIP: {$company->getNip()}</li>
                <li>Telefon: {$company->getPhone()}</li>
                <li>Email: {$company->getEmail()}</li>
            </ul>
            <p>Dane właściciela:</p>
            <ul>
                <li>Imię i nazwisko: {$employee->getFullName()}</li>
                <li>Email: {$employee->getEmail()}</li>
                <li>Telefon: {$employee->getPhone()}</li>
            </ul>
            <p>Prosimy o weryfikację i aktywację konta.</p>
        ";
    }
}
