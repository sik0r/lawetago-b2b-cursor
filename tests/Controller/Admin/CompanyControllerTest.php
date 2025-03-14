<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Admin;
use App\Entity\Company;
use App\Entity\Employee;
use App\Repository\AdminRepository;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyControllerTest extends WebTestCase
{
    private function createTestAdmin(): Admin
    {
        $container = static::getContainer();
        $adminRepository = $container->get(AdminRepository::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $admin = new Admin();
        $admin->setEmail('test-admin@example.com');
        $admin->setPassword($passwordHasher->hashPassword($admin, 'password123'));

        $adminRepository->save($admin, true);

        return $admin;
    }

    private function createTestCompanies(int $count = 3): void
    {
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        // Create a test employee as owner
        $employee = new Employee();
        $employee->setEmail('test-employee@example.com');
        $employee->setPassword('password');
        $employee->setFirstName('Test');
        $employee->setLastName('Employee');
        $employee->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($employee);

        // Create test companies
        for ($i = 1; $i <= $count; ++$i) {
            $company = new Company();
            $company->setName('Test Company '.$i);
            $company->setAddress('Test Address '.$i);
            $company->setNip('123456789'.$i);
            $company->setPhone('123456789');
            $company->setEmail('company'.$i.'@example.com');
            $company->setStatus(0 === $i % 2 ? Company::STATUS_ACTIVE : Company::STATUS_PENDING);
            $company->setOwner($employee);

            $entityManager->persist($company);
        }

        $entityManager->flush();
    }

    public function testCompanyListWithAuthentication(): void
    {
        $client = static::createClient();

        // Create test admin and companies
        $this->createTestAdmin();
        $this->createTestCompanies(5);

        // Log in as admin
        $client->request('GET', '/admin/login');
        $client->submitForm('Zaloguj się', [
            '_username' => 'test-admin@example.com',
            '_password' => 'password123',
        ]);

        // Visit the companies page
        $crawler = $client->request('GET', '/admin/companies');
        $this->assertResponseIsSuccessful();

        // Check if the page contains the expected elements
        $this->assertSelectorTextContains('h3', 'Lista firm');

        // Check if all companies are displayed
        $companyRows = $crawler->filter('tbody tr');
        $this->assertGreaterThanOrEqual(5, $companyRows->count());

        // Check if company details are displayed correctly
        $this->assertSelectorTextContains('tbody tr td:nth-child(2) div', 'Test Company');

        // Check if pagination elements exist when needed
        $container = static::getContainer();
        $companyRepository = $container->get(CompanyRepository::class);
        $totalCompanies = $companyRepository->count([]);

        if ($totalCompanies > 10) {
            $this->assertSelectorExists('nav[aria-label="Pagination"]');
        }
    }

    public function testCompanyListWithoutAuthentication(): void
    {
        $client = static::createClient();

        // Try to access the companies page without authentication
        $client->request('GET', '/admin/companies');

        // We should be redirected to the login page
        $this->assertResponseRedirects('/admin/login');
    }

    public function testCompanyDetailsWithAuthentication(): void
    {
        $client = static::createClient();

        // Create test admin and companies
        $this->createTestAdmin();
        $this->createTestCompanies(1);

        // Get the company ID
        $container = static::getContainer();
        $companyRepository = $container->get(CompanyRepository::class);
        $company = $companyRepository->findOneBy(['name' => 'Test Company 1']);
        $this->assertNotNull($company, 'Test company not found');

        // Log in as admin
        $client->request('GET', '/admin/login');
        $client->submitForm('Zaloguj się', [
            '_username' => 'test-admin@example.com',
            '_password' => 'password123',
        ]);

        // Visit the company details page
        $crawler = $client->request('GET', '/admin/companies/'.$company->getId());
        $this->assertResponseIsSuccessful();

        // Check if the page contains the expected elements
        $this->assertSelectorTextContains('h3', $company->getName());

        // Check for NIP - using a more specific selector to target the NIP value
        $this->assertSelectorExists('dt:contains("NIP")');
        $this->assertSelectorExists('dd:contains("'.$company->getNip().'")');

        // Check for email - using a more specific selector
        $this->assertSelectorExists('dt:contains("Email")');
        $this->assertSelectorExists('dd:contains("'.$company->getEmail().'")');

        // Check if owner information is displayed - using more specific selectors
        $this->assertSelectorExists('dt:contains("Właściciel")');
        if ($company->getOwner()) {
            $this->assertSelectorExists('dd:contains("'.$company->getOwner()->getEmail().'")');
        }
    }

    public function testCompanyDetailsWithoutAuthentication(): void
    {
        $client = static::createClient();

        // Create test companies
        $this->createTestCompanies(1);

        // Get the company ID
        $container = static::getContainer();
        $companyRepository = $container->get(CompanyRepository::class);
        $company = $companyRepository->findOneBy(['name' => 'Test Company 1']);
        $this->assertNotNull($company, 'Test company not found');

        // Try to access the company details page without authentication
        $client->request('GET', '/admin/companies/'.$company->getId());

        // We should be redirected to the login page
        $this->assertResponseRedirects('/admin/login');
    }

    public function testCompanyDetailsWithNonExistentId(): void
    {
        $client = static::createClient();

        // Create test admin
        $this->createTestAdmin();

        // Log in as admin
        $client->request('GET', '/admin/login');
        $client->submitForm('Zaloguj się', [
            '_username' => 'test-admin@example.com',
            '_password' => 'password123',
        ]);

        // Visit a non-existent company details page
        $client->request('GET', '/admin/companies/99999');

        // Should return a 404 Not Found response
        $this->assertResponseStatusCodeSame(404);
    }

    public function testCompanyDetailsWithOwnerRelationship(): void
    {
        $client = static::createClient();

        // Create test admin
        $this->createTestAdmin();

        // Create a specific employee to be the owner
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $owner = new Employee();
        $owner->setEmail('owner-test@example.com');
        $owner->setPassword('password123');
        $owner->setFirstName('John');
        $owner->setLastName('Doe');
        $owner->setPhone('+48123456789');
        $owner->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($owner);

        // Create a company with this specific owner
        $company = new Company();
        $company->setName('Owner Test Company');
        $company->setAddress('Owner Test Address');
        $company->setNip('9876543210');
        $company->setPhone('987654321');
        $company->setEmail('owner-company@example.com');
        $company->setStatus(Company::STATUS_ACTIVE);
        $company->setOwner($owner);

        $entityManager->persist($company);
        $entityManager->flush();

        // Log in as admin
        $client->request('GET', '/admin/login');
        $client->submitForm('Zaloguj się', [
            '_username' => 'test-admin@example.com',
            '_password' => 'password123',
        ]);

        // Visit the company details page
        $crawler = $client->request('GET', '/admin/companies/'.$company->getId());
        $this->assertResponseIsSuccessful();

        // Check if the company name is displayed correctly
        $this->assertSelectorTextContains('h3', 'Owner Test Company');

        // Check if the owner information is displayed correctly
        $this->assertSelectorExists('dt:contains("Właściciel")');

        // Check for owner's full name
        $this->assertSelectorExists('dd:contains("John Doe")');

        // Check for owner's email
        $this->assertSelectorExists('dd:contains("owner-test@example.com")');

        // Verify the owner's full information is displayed in the expected format
        $ownerInfo = $owner->getFullName().' ('.$owner->getEmail().')';
        $this->assertSelectorExists('dd:contains("'.$ownerInfo.'")');
    }

    public function testCompanyActivation(): void
    {
        $client = static::createClient();

        // Create test data
        $this->createTestCompanies();
        $admin = $this->createTestAdmin();

        // Log in as admin with the correct firewall name
        $client->loginUser($admin, 'admin');

        // Find a pending company
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $companyRepository = $entityManager->getRepository(Company::class);
        $pendingCompany = $companyRepository->findOneBy(['status' => Company::STATUS_PENDING]);

        $this->assertNotNull($pendingCompany, 'No pending company found for testing');

        // Send activation request
        $client->request(
            'POST',
            '/admin/companies/'.$pendingCompany->getId().'/activate',
            [],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // Check response
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Assert response structure
        $this->assertArrayHasKey('success', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('company', $responseData);
        $this->assertTrue($responseData['success']);

        // Refresh company from database
        $entityManager->refresh($pendingCompany);

        // Assert company is now active
        $this->assertEquals(Company::STATUS_ACTIVE, $pendingCompany->getStatus());
        $this->assertNotNull($pendingCompany->getVerifiedAt());
    }

    public function testCompanyActivationWithInvalidId(): void
    {
        $client = static::createClient();

        // Create test admin
        $admin = $this->createTestAdmin();

        // Log in as admin with the correct firewall name
        $client->loginUser($admin, 'admin');

        // Send activation request with invalid ID
        $client->request(
            'POST',
            '/admin/companies/99999/activate',
            [],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // Check response
        $this->assertResponseStatusCodeSame(404);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Assert response structure
        $this->assertArrayHasKey('error', $responseData);
    }

    public function testCompanyActivationWithAlreadyActiveCompany(): void
    {
        $client = static::createClient();

        // Create test data
        $this->createTestCompanies();
        $admin = $this->createTestAdmin();

        // Log in as admin with the correct firewall name
        $client->loginUser($admin, 'admin');

        // Find an active company
        $container = static::getContainer();
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $companyRepository = $entityManager->getRepository(Company::class);
        $activeCompany = $companyRepository->findOneBy(['status' => Company::STATUS_ACTIVE]);

        $this->assertNotNull($activeCompany, 'No active company found for testing');

        // Send activation request
        $client->request(
            'POST',
            '/admin/companies/'.$activeCompany->getId().'/activate',
            [],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // Check response
        $this->assertResponseStatusCodeSame(400);
        $responseData = json_decode($client->getResponse()->getContent(), true);

        // Assert response structure
        $this->assertArrayHasKey('error', $responseData);
        $this->assertStringContainsString('Tylko firmy oczekujące na weryfikację', $responseData['error']);
    }
}
