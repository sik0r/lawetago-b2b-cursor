<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Company;
use App\Entity\Employee;
use App\Service\CompanyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;

class CompanyServiceTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private CompanyService $companyService;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->mailer = $container->get('mailer');
        $this->companyService = $container->get(CompanyService::class);
    }

    public function testActivateCompany(): void
    {
        // Create a real employee
        $owner = new Employee();
        $owner->setEmail('test-owner@example.com');
        $owner->setPassword('password');
        $owner->setFirstName('Test');
        $owner->setLastName('Owner');
        $owner->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($owner);

        // Create a real company with pending status
        $company = new Company();
        $company->setName('Test Company');
        $company->setAddress('Test Address');
        $company->setNip('1234567890');
        $company->setPhone('123456789');
        $company->setEmail('test-company@example.com');
        $company->setStatus(Company::STATUS_PENDING);
        $company->setOwner($owner);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Activate the company
        $result = $this->companyService->activateCompany($company);

        // Assert the result is the same company
        $this->assertSame($company, $result);

        // Assert company is now active
        $this->assertEquals(Company::STATUS_ACTIVE, $company->getStatus());
        $this->assertNotNull($company->getVerifiedAt());

        // We don't need to test if an email was actually sent as that's not the responsibility
        // of this test, and we're using the real mailer service which is configured for the test environment
    }

    public function testActivateCompanyWithInvalidStatus(): void
    {
        // Create a real company with active status
        $company = new Company();
        $company->setName('Active Test Company');
        $company->setAddress('Test Address');
        $company->setNip('0987654321');
        $company->setPhone('987654321');
        $company->setEmail('active-company@example.com');
        $company->setStatus(Company::STATUS_ACTIVE);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Expect an exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Tylko firmy oczekujące na weryfikację mogą zostać aktywowane.');

        // Call the service method
        $this->companyService->activateCompany($company);
    }

    public function testActivateCompanyWithoutOwner(): void
    {
        // Create a real company with pending status but no owner
        $company = new Company();
        $company->setName('Company Without Owner');
        $company->setAddress('Test Address');
        $company->setNip('5678901234');
        $company->setPhone('567890123');
        $company->setEmail('no-owner-company@example.com');
        $company->setStatus(Company::STATUS_PENDING);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        // Call the service method
        $result = $this->companyService->activateCompany($company);

        // Assert the result is the same company
        $this->assertSame($company, $result);

        // Assert company is now active
        $this->assertEquals(Company::STATUS_ACTIVE, $company->getStatus());
        $this->assertNotNull($company->getVerifiedAt());
    }
}
