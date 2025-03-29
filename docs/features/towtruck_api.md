Okay, let's outline the implementation plan for adding a HATEOAS-compliant REST API endpoint for listing tow truck advertisements.

### **Implementation Plan: REST API for Tow Truck Advertisements**

**Goal:** Create a paginated REST API endpoint `/api/towtruck` that lists `Advertisement` entities, adhering to HATEOAS principles (Level 4).

---

**1. Foundational Observations**

*   **Entity:** We will use the existing `App\Entity\Advertisement` entity (`src/Entity/Advertisement.php`).
*   **Framework:** The project uses Symfony 6+.
*   **Requirement:** The API needs to return a list of advertisements with pagination (default 15 items/page).
*   **HATEOAS:** The response must include hypermedia links for navigation (self, first, last, next, prev) and links for individual resources, achieving Level 4 of the Richardson Maturity Model. Reference: [HATEOAS Driven REST APIs](https://restfulapi.net/hateoas/)
*   **Controller Location:** A new controller will be created, preferably within an `Api` subdirectory: `src/Controller/Api/`.
*   **Endpoint:** The primary endpoint will be `GET /api/towtruck`.
*   **Data Format:** JSON will be used for request and response bodies. We should consider using the `application/hal+json` media type if adhering strictly to the HAL specification.

**2. Thorough Questioning and Reasoning**

*   **Pagination Strategy:**
    *   *Option 1:* Use Doctrine's Paginator directly. Requires manual calculation of total pages and link generation.
    *   *Option 2:* Use `knplabs/knp-paginator-bundle`. Simplifies pagination logic and provides metadata. **Chosen Approach:** This bundle integrates well with Symfony and Doctrine, reducing boilerplate code for pagination calculations.
*   **HATEOAS Implementation Strategy:**
    *   *Option 1:* Manual Link Generation. Build link arrays directly in the controller using `UrlGeneratorInterface`. Can be verbose.
    *   *Option 2:* Use `willdurand/hateoas-bundle`. Provides annotations and configuration to automatically add links based on entity relations and routes. Requires `jms/serializer-bundle` or integration with Symfony's Serializer.
    *   *Option 3:* Use `api-platform/core`. A full-fledged framework for building hypermedia-driven APIs. Might be overkill for a single endpoint but powerful for larger APIs.
    *   **Chosen Approach:** Start with manual link generation using Symfony's `Serializer` and `UrlGeneratorInterface` combined with KnpPaginatorBundle's data. This avoids adding larger dependencies like HateoasBundle or API Platform immediately but keeps the door open for refactoring later if the API grows. We will structure the response loosely based on HAL (`_links`, `_embedded`).
*   **Serialization:**
    *   Symfony's `Serializer` component (`symfony/serializer-pack`) will be used.
    *   We need to define serialization groups (`#[Groups]` attribute) on the `Advertisement` entity to control which fields are exposed in the API response. This prevents accidentally exposing sensitive or unnecessary data.
*   **Individual Resource Endpoint:**
    *   To provide meaningful `self` links for each advertisement in the list, we also need an endpoint to retrieve a single advertisement, e.g., `GET /api/towtruck/{id}`. This is crucial for HATEOAS.
*   **Error Handling:** Standard Symfony exceptions (e.g., `NotFoundHttpException`) should be handled, returning appropriate JSON error responses and HTTP status codes.

**3. Final Answer: Step-by-Step Implementation Plan**

1.  **Install Dependencies:**
    *   Ensure `symfony/serializer-pack` is installed: `composer require symfony/serializer-pack`
    *   Install KnpPaginatorBundle: `composer require knplabs/knp-paginator-bundle`
    *   Enable the bundle in `config/bundles.php` if not done automatically.
    *   Configure KnpPaginatorBundle if necessary (usually defaults are fine).

2.  **Define Serialization Groups:**
    *   Edit `src/Entity/Advertisement.php`.
    *   Add the `#[Groups(['advertisement:read'])]` attribute to the properties you want to expose via the API (e.g., `id`, `title`, `description`, `serviceArea`, `servicesOffered`, `status`, `createdAt`, `updatedAt`). *Do not* add it to sensitive or internal fields or potentially large relations unless explicitly needed.

3.  **Create Repository Method (Optional but Recommended):**
    *   In `src/Repository/AdvertisementRepository.php`, create a method like `createQueryBuilderForApiListing()` that returns a `QueryBuilder` instance, potentially pre-filtering for active advertisements if required. This keeps the controller cleaner.
    *   Example:
        ```php
        public function createQueryBuilderForApiListing(): QueryBuilder
        {
            return $this->createQueryBuilder('a')
                // ->andWhere('a.status = :status') // Optional: Filter by status
                // ->setParameter('status', Advertisement::STATUS_ACTIVE)
                ->orderBy('a.createdAt', 'DESC');
        }
        ```

4.  **Create the API Controller:**
    *   Create the directory `src/Controller/Api/`.
    *   Create the file `src/Controller/Api/TowTruckController.php`.
    *   Define the `TowTruckController` class extending `AbstractController`.

5.  **Implement List Endpoint (`GET /api/towtruck`):**
    *   Inject `Request`, `AdvertisementRepository`, `PaginatorInterface`, `SerializerInterface`, `UrlGeneratorInterface`.
    *   Define the action method with the route `#[Route('/api/towtruck', name: 'api_towtruck_list', methods: ['GET'])]`.
    *   Get `page` (default 1) and `limit` (default 15) from the `Request` query parameters.
    *   Get the `QueryBuilder` from the repository method created in step 3.
    *   Use `$paginator->paginate()` to get the paginated results (`PaginationInterface $pagination`).
    *   Prepare the response data array:
        *   `_links`: Contains HATEOAS links (`self`, `first`, `last`, `next`, `prev`) generated using `UrlGeneratorInterface` and pagination data (`$pagination->getCurrentPageNumber()`, `$pagination->getPageCount()`, etc.).
        *   `page`: Current page number.
        *   `limit`: Items per page.
        *   `totalItems`: Total number of items across all pages (`$pagination->getTotalItemCount()`).
        *   `totalPages`: Total number of pages (`$pagination->getPageCount()`).
        *   `_embedded`: Contains the actual list of advertisements.
            *   `items`: An array to hold serialized advertisement data.
    *   Iterate through `$pagination->getItems()`:
        *   For each `Advertisement $advertisement`, create an array representing its data.
        *   Add a `_links` key to this item array containing a `self` link pointing to the individual resource route (e.g., `api_towtruck_show`, see step 6), generated using `UrlGeneratorInterface`.
        *   Add this item array to the `_embedded['items']` array in the main response data.
    *   Use `$serializer->serialize()` to convert the main response data array to JSON, passing the serialization group `['groups' => 'advertisement:read']` in the context.
    *   Return a `JsonResponse` with the JSON string and `Content-Type: application/json` (or `application/hal+json`).

6.  **Implement Show Endpoint (`GET /api/towtruck/{id}`):**
    *   In `TowTruckController`, add a new action method `show(int $id, AdvertisementRepository $repository, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator)`.
    *   Add the route `#[Route('/api/towtruck/{id}', name: 'api_towtruck_show', requirements: ['id' => '\d+'], methods: ['GET'])]`.
    *   Fetch the `Advertisement` using `$repository->find($id)`. Throw `NotFoundHttpException` if not found.
    *   Prepare the data array for the single advertisement.
    *   Add a `_links` key with at least a `self` link pointing to its own route (`api_towtruck_show`). Potentially add links to related resources if applicable (e.g., link to the company).
    *   Use `$serializer->serialize()` with the `advertisement:read` group.
    *   Return a `JsonResponse`.

7.  **Testing:**
    *   Use tools like Postman or `curl` to send `GET` requests to `/api/towtruck`.
    *   Verify the response structure, pagination (`?page=2`, `?limit=5`), HATEOAS links (`_links`), embedded items (`_embedded.items`), and individual item links.
    *   Test the `/api/towtruck/{id}` endpoint for individual advertisements.
    *   Test edge cases (first page, last page, non-existent page, non-existent ID).
    *   Write integrations tests in tests/* 

This plan provides a clear path to implementing the required REST API endpoint with pagination and HATEOAS compliance using standard Symfony components and the KnpPaginatorBundle.
