<?php

declare(strict_types=1);

namespace App\Tests\Controller\Admin;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityControllerTest extends WebTestCase
{
    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();

        // Create a test admin user
        $container = $client->getContainer();
        $adminRepository = $container->get(AdminRepository::class);
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $admin = new Admin();
        $admin->setEmail('test-admin@example.com');
        $admin->setPassword($passwordHasher->hashPassword($admin, 'password123'));

        $adminRepository->save($admin, true);

        // Visit the login page
        $crawler = $client->request('GET', '/admin/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Panel Administracyjny');

        // Submit the login form
        $form = $crawler->selectButton('Zaloguj się')->form();
        $form['_username'] = 'test-admin@example.com';
        $form['_password'] = 'password123';

        $client->submit($form);

        // Check if we're redirected to the dashboard
        $this->assertResponseRedirects('/admin');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Witaj w Panelu Administracyjnym LawetaGO');
    }

    public function testFailedLogin(): void
    {
        $client = static::createClient();

        // Visit the login page
        $crawler = $client->request('GET', '/admin/login');
        $this->assertResponseIsSuccessful();

        // Submit the login form with incorrect credentials
        $form = $crawler->selectButton('Zaloguj się')->form();
        $form['_username'] = 'nonexistent@example.com';
        $form['_password'] = 'wrongpassword';

        $client->submit($form);

        // We should be redirected back to the login page with an error
        $this->assertResponseRedirects('/admin/login');

        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.bg-red-100.border-red-400'); // Error message container
    }
}
