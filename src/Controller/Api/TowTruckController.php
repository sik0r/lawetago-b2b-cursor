<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TowTruckController extends AbstractController
{
    #[Route('/api/towtruck', name: 'api_towtruck_list', methods: ['GET'])]
    public function list(
        Request $request,
        AdvertisementRepository $repository,
        PaginatorInterface $paginator,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        // Get page and limit from request, with defaults
        $page = max(1, $request->query->getInt('page', 1));
        $limit = max(1, min(50, $request->query->getInt('limit', 15)));
        
        // Get the query builder from repository
        $queryBuilder = $repository->createQueryBuilderForApiListing();
        
        // Use KnpPaginator to paginate results
        $pagination = $paginator->paginate(
            $queryBuilder,
            $page,
            $limit
        );
        
        // Calculate total pages
        $totalPages = ceil($pagination->getTotalItemCount() / $limit);
        
        // Prepare the HATEOAS response data
        $responseData = [
            '_links' => [
                'self' => [
                    'href' => $urlGenerator->generate('api_towtruck_list', [
                        'page' => $page,
                        'limit' => $limit,
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
                'first' => [
                    'href' => $urlGenerator->generate('api_towtruck_list', [
                        'page' => 1,
                        'limit' => $limit,
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
                'last' => [
                    'href' => $urlGenerator->generate('api_towtruck_list', [
                        'page' => $totalPages ?: 1,
                        'limit' => $limit,
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ],
            'page' => $page,
            'limit' => $limit,
            'totalItems' => $pagination->getTotalItemCount(),
            'totalPages' => $totalPages,
            '_embedded' => [
                'items' => [],
            ],
        ];
        
        // Add next and prev links if applicable
        if ($page < $totalPages) {
            $responseData['_links']['next'] = [
                'href' => $urlGenerator->generate('api_towtruck_list', [
                    'page' => $page + 1,
                    'limit' => $limit,
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }
        
        if ($page > 1) {
            $responseData['_links']['prev'] = [
                'href' => $urlGenerator->generate('api_towtruck_list', [
                    'page' => $page - 1,
                    'limit' => $limit,
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }
        
        // Add advertisement items with their self links
        foreach ($pagination->getItems() as $advertisement) {
            /** @var Advertisement $advertisement */
            $itemData = [
                'id' => $advertisement->getId(),
                'title' => $advertisement->getTitle(),
                'description' => $advertisement->getDescription(),
                'serviceArea' => $advertisement->getServiceArea(),
                'servicesOffered' => $advertisement->getServicesOffered(),
                'status' => $advertisement->getStatus(),
                'createdAt' => $advertisement->getCreatedAt()?->format(\DateTimeInterface::ATOM),
                'updatedAt' => $advertisement->getUpdatedAt()?->format(\DateTimeInterface::ATOM),
                '_links' => [
                    'self' => [
                        'href' => $urlGenerator->generate('api_towtruck_show', [
                            'id' => $advertisement->getId(),
                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                    ],
                ],
            ];
            
            $responseData['_embedded']['items'][] = $itemData;
        }
        
        // Serialize data to JSON with advertisement:read group
        $json = $serializer->serialize($responseData, 'json');
        
        return new JsonResponse(
            $json,
            Response::HTTP_OK,
            ['Content-Type' => 'application/hal+json'],
            true
        );
    }
    
    #[Route('/api/towtruck/{id}', name: 'api_towtruck_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        int $id,
        AdvertisementRepository $repository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        // Find the advertisement
        $advertisement = $repository->find($id);
        
        // If not found, return 404
        if (!$advertisement) {
            return new JsonResponse(
                ['error' => 'Advertisement not found'],
                Response::HTTP_NOT_FOUND
            );
        }
        
        // Prepare response data with HATEOAS links
        $responseData = [
            'id' => $advertisement->getId(),
            'title' => $advertisement->getTitle(),
            'description' => $advertisement->getDescription(),
            'serviceArea' => $advertisement->getServiceArea(),
            'servicesOffered' => $advertisement->getServicesOffered(),
            'status' => $advertisement->getStatus(),
            'createdAt' => $advertisement->getCreatedAt()?->format(\DateTimeInterface::ATOM),
            'updatedAt' => $advertisement->getUpdatedAt()?->format(\DateTimeInterface::ATOM),
            'company' => $advertisement->getCompany() ? [
                'name' => $advertisement->getCompany()->getName(),
                'address' => $advertisement->getCompany()->getAddress(),
                'nip' => $advertisement->getCompany()->getNip(),
            ] : null,
            '_links' => [
                'self' => [
                    'href' => $urlGenerator->generate('api_towtruck_show', [
                        'id' => $advertisement->getId(),
                    ], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
                'collection' => [
                    'href' => $urlGenerator->generate('api_towtruck_list', [], UrlGeneratorInterface::ABSOLUTE_URL),
                ],
            ],
        ];
        
        // Serialize data to JSON
        $json = $serializer->serialize($responseData, 'json', ['groups' => ['advertisement:read']]);
        
        return new JsonResponse(
            $json,
            Response::HTTP_OK,
            ['Content-Type' => 'application/hal+json'],
            true
        );
    }
} 