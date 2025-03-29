<?php

declare(strict_types=1);

namespace App\Tests\Controller\Api;

use App\Entity\Advertisement;
use App\Entity\Company;
use App\Entity\Employee;
use App\Repository\AdvertisementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TowTruckControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private AdvertisementRepository $advertisementRepository;
    
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->advertisementRepository = $container->get(AdvertisementRepository::class);
        
        // Ensure the database is empty before each test
        $this->clearEntities();
        
        // Create test data
        $this->createTestData();
    }
    
    private function clearEntities(): void
    {
        // Delete all advertisements
        $advertisements = $this->advertisementRepository->findAll();
        foreach ($advertisements as $advertisement) {
            $this->entityManager->remove($advertisement);
        }
        $this->entityManager->flush();
    }
    
    private function createTestData(): void
    {
        // Create an employee first
        $employee = new Employee();
        $employee->setEmail('test-employee@example.com');
        $employee->setPassword('password');
        $employee->setFirstName('Test');
        $employee->setLastName('Employee');
        $employee->setCreatedAt(new \DateTimeImmutable());
        
        $this->entityManager->persist($employee);
        
        // Create a company
        $company = new Company();
        $company->setName('Test Company');
        $company->setAddress('Test Address');
        $company->setNip('1234567890');
        $company->setPhone('123456789');
        $company->setEmail('test@example.com');
        $company->setStatus(Company::STATUS_ACTIVE);
        $company->setOwner($employee);
        
        $this->entityManager->persist($company);
        
        // Set up the bidirectional relationship
        $employee->addOwnedCompany($company);
        $employee->addRole(Employee::ROLE_COMPANY_OWNER);
        
        // Flush to save the employee and company
        $this->entityManager->flush();
        
        // Create some test advertisements
        for ($i = 1; $i <= 20; $i++) {
            $advertisement = new Advertisement();
            $advertisement->setTitle("Test Advertisement $i");
            $advertisement->setDescription("Description for advertisement $i");
            $advertisement->setServiceArea("Service area $i");
            $advertisement->setServicesOffered("Services offered $i");
            $advertisement->setStatus(Advertisement::STATUS_ACTIVE);
            $advertisement->setCompany($company);
            $advertisement->setCreatedBy($employee);
            
            $this->entityManager->persist($advertisement);
        }
        
        $this->entityManager->flush();
    }
    
    public function testListAdvertisements(): void
    {
        $this->client->request('GET', '/api/towtruck');
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/hal+json');
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        // Check response structure
        $this->assertArrayHasKey('_links', $response);
        $this->assertArrayHasKey('self', $response['_links']);
        $this->assertArrayHasKey('first', $response['_links']);
        $this->assertArrayHasKey('last', $response['_links']);
        
        $this->assertArrayHasKey('page', $response);
        $this->assertArrayHasKey('limit', $response);
        $this->assertArrayHasKey('totalItems', $response);
        $this->assertArrayHasKey('totalPages', $response);
        
        $this->assertArrayHasKey('_embedded', $response);
        $this->assertArrayHasKey('items', $response['_embedded']);
        
        // Check that we have items
        $this->assertNotEmpty($response['_embedded']['items']);
        
        // Check that each item has the correct structure
        foreach ($response['_embedded']['items'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('serviceArea', $item);
            $this->assertArrayHasKey('servicesOffered', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('createdAt', $item);
            
            $this->assertArrayHasKey('_links', $item);
            $this->assertArrayHasKey('self', $item['_links']);
        }
    }
    
    public function testListAdvertisementsWithPagination(): void
    {
        // Test with custom pagination
        $this->client->request('GET', '/api/towtruck?page=2&limit=5');
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        // Check pagination info
        $this->assertEquals(2, $response['page']);
        $this->assertEquals(5, $response['limit']);
        $this->assertEquals(5, count($response['_embedded']['items']));
        
        // Check that prev and next links exist
        $this->assertArrayHasKey('prev', $response['_links']);
        $this->assertArrayHasKey('next', $response['_links']);
    }
    
    public function testShowAdvertisement(): void
    {
        // Get an advertisement ID to test
        $advertisement = $this->advertisementRepository->findOneBy(['status' => Advertisement::STATUS_ACTIVE]);
        $id = $advertisement->getId();
        
        $this->client->request('GET', "/api/towtruck/$id");
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/hal+json');
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        // Check response structure
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('serviceArea', $response);
        $this->assertArrayHasKey('servicesOffered', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('createdAt', $response);
        
        $this->assertArrayHasKey('_links', $response);
        $this->assertArrayHasKey('self', $response['_links']);
        $this->assertArrayHasKey('collection', $response['_links']);
        
        // Check that the data is correct
        $this->assertEquals($id, $response['id']);
        $this->assertEquals($advertisement->getTitle(), $response['title']);
        $this->assertEquals($advertisement->getStatus(), $response['status']);
    }
    
    public function testShowNonExistentAdvertisement(): void
    {
        $this->client->request('GET', '/api/towtruck/999999');
        
        $this->assertResponseStatusCodeSame(404);
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        
        // Check error response
        $this->assertArrayHasKey('error', $response);
    }
} 