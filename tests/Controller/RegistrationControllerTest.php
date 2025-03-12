<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistrationPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/rejestracja');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Rejestracja Firmy');
    }

    public function testRegistrationFormValidation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/rejestracja');

        $form = $crawler->selectButton('Zarejestruj')->form();
        $client->submit($form, []);

        $this->assertResponseStatusCodeSame(422); // Form should return 422 Unprocessable Entity with errors
        $this->assertSelectorExists('.form-text.text-danger'); // Error messages should be displayed
    }

    public function testConfirmationPageRenders(): void
    {
        $client = static::createClient();
        $client->request('GET', '/rejestracja/potwierdzenie');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Rejestracja przyjęta');
    }
    
    public function testSuccessfulRegistrationWithDatabaseCheck(): void
    {
        $client = static::createClient();
        
        // Generate a unique email to avoid conflicts
        $uniqueId = uniqid();
        $email = "test{$uniqueId}@example.com";
        
        // Ensure NIP is numeric only (10 digits)
        $nip = '1234567890';
        
        $crawler = $client->request('GET', '/rejestracja');
        $this->assertResponseIsSuccessful();
        
        $form = $crawler->selectButton('Zarejestruj')->form();
        $form['company_registration[name]'] = 'Test Company';
        $form['company_registration[address]'] = 'Test Address 123';
        $form['company_registration[nip]'] = $nip;
        $form['company_registration[phone]'] = '123456789';
        $form['company_registration[email]'] = $email;
        $form['company_registration[ownerFirstName]'] = 'John';
        $form['company_registration[ownerLastName]'] = 'Doe';
        $form['company_registration[plainPassword][first]'] = 'Password123!';
        $form['company_registration[plainPassword][second]'] = 'Password123!';
        $form['company_registration[agreeTerms]'] = 1;
        
        $client->submit($form);
        
        // Check if we're redirected to the confirmation page
        $response = $client->getResponse();
        if ($response->getStatusCode() !== 302) {
            // Dump form errors if validation fails
            $this->fail('Form submission failed with status code ' . $response->getStatusCode() . 
                        '. Response content: ' . $response->getContent());
        }
        
        $this->assertResponseRedirects('/rejestracja/potwierdzenie');
        
        // Follow the redirect
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Rejestracja przyjęta');
        
        // Now check the database
        $companyRepository = static::getContainer()->get('doctrine')->getRepository(Company::class);
        $userRepository = static::getContainer()->get('doctrine')->getRepository(Employee::class);
        
        // Find the newly created Company
        $company = $companyRepository->findOneBy(['nip' => $nip]);
        $this->assertNotNull($company, 'Company with the given NIP was not found in the database');
        $this->assertEquals('Test Company', $company->getName());
        
        // Find the newly created User/Employee
        $user = $userRepository->findOneBy(['email' => $email]);
        $this->assertNotNull($user, 'User/Employee with the given email was not found in the database');
        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        
        // Verify the relationship
        $this->assertSame($user, $company->getOwner(), 'The Company owner is not properly set');
        
        // Clean up the database
        $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        if ($company) {
            $entityManager->remove($company);
        }
        if ($user) {
            $entityManager->remove($user);
        }
        $entityManager->flush();
    }
}
