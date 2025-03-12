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
}
