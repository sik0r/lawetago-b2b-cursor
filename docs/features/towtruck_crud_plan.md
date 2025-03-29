Okay, let's outline a plan to implement the functionality for adding tow truck advertisements (`ogłoszenia z lawetą`) within the Backoffice section of your application.

### 1. Foundational Observations

*   **Goal:** Enable authenticated `Employee` users to create, view, edit, and delete advertisements associated with their assigned `Company` within the `Backoffice` area.
*   **Core Entities:** `Employee` (creator/manager), `Company` (owner of the advertisement). A new entity, `Advertisement`, is required.
*   **Environment:** Symfony 7+, PHP 8.3, Twig, Tailwind CSS, Docker, PER-CS 2.0.0, Makefile for CLI commands.
*   **Guidelines:** Adherence to `backend-guidelines.md` and `frontend-guidelines.md` is mandatory.
*   **Scope:** Implementation includes backend logic (Entity, Repository, Controller, Form, Security) and frontend templates (Twig with Tailwind CSS).

### 2. Thorough Questioning and Reasoning

**A. What information should an `Advertisement` contain?**

*   **Research Synthesis:** Based on the provided web search results about advertising towing businesses, key information includes:
    *   **Identification:** Title/Headline, Link to the owning `Company`.
    *   **Content:** Detailed Description (services offered, unique selling points), List of Services (e.g., emergency towing, roadside assistance, vehicle transport), Service Area description (cities, regions, radius).
    *   **Visuals:** Photos (e.g., tow trucks, team) - *This adds complexity (file uploads, storage) and might be deferred to a later phase, but should be considered.*
    *   **Contact:** Implicitly derived from the linked `Company` (phone, email).
    *   **Operational:** Operating Hours (could be derived from `Company` or specific to the ad), Special Offers/Discounts.
    *   **Metadata:** Status (e.g., Draft, Active, Inactive), Creation/Update Timestamps, Link to the creating `Employee`.
*   **Initial Field Selection:** For a first iteration, we'll focus on essential text-based fields and relationships: Title, Description, Service Area (text), Services Offered (text), Status, Company (Relation), CreatedBy (Relation), Timestamps. Image uploads can be a subsequent enhancement.

**B. How should this integrate with the existing structure?**

*   **Entity Relationship:**
    *   `Advertisement` will have a `ManyToOne` relationship with `Company`.
    *   `Advertisement` will have a `ManyToOne` relationship with `Employee` (as `createdBy`).
    *   `Company` could have a `OneToMany` relationship back to `Advertisement`.
    *   `Employee` could have a `OneToMany` relationship back to `Advertisement` (for ads they created).
*   **Backend Structure (Following `backend-guidelines.md`):**
    *   New Entity: `src/Entity/Advertisement.php`
    *   New Repository: `src/Repository/AdvertisementRepository.php`
    *   New Controller: `src/Controller/Backoffice/AdvertisementController.php` (using standard CRUD actions: index, new, create, show, edit, update, delete).
    *   New Form Type: `src/Form/Backoffice/AdvertisementType.php`
    *   Services: If logic becomes complex (e.g., image handling, complex validation), create services under `src/Service/`. Initially, controller logic might suffice.
*   **Frontend Structure (Following `frontend-guidelines.md`):**
    *   New Templates: Under `templates/backoffice/advertisement/` (e.g., `index.html.twig`, `new.html.twig`, `edit.html.twig`, `_form.html.twig`, `_delete_form.html.twig`).
    *   Templates will extend `templates/backoffice/base.html.twig`.
    *   Styling will use Tailwind CSS classes provided via CDN as per the base template.
    *   Navigation: Add a link to the Advertisement management section in the sidebar (`templates/backoffice/base.html.twig`).
*   **Security:** Access control needs to ensure:
    *   Only logged-in employees (`ROLE_COMPANY_EMPLOYEE` or `ROLE_COMPANY_OWNER`) can access the advertisement section.
    *   An employee can only manage advertisements belonging to the `Company` they are associated with (either as owner or employee). Symfony Voters are suitable for this fine-grained control.

**C. What are the key implementation steps?**

1.  **Database Schema:** Define and create the `Advertisement` entity and its relationships.
2.  **Backend Logic:** Implement the Controller, Form Type, and basic Repository methods.
3.  **Frontend Views:** Create Twig templates for listing, creating, and editing advertisements.
4.  **Routing & Navigation:** Define routes and add sidebar links.
5.  **Security:** Implement access control rules.
6.  **Testing:** Add basic tests.

### 3. Final Answer: Implementation Plan

Here is the step-by-step plan:

1.  **Define `Advertisement` Entity:**
    *   Create `src/Entity/Advertisement.php`.
    *   Add fields: `id` (int, PK), `title` (string), `description` (text), `serviceArea` (text), `servicesOffered` (text), `status` (string, e.g., 'draft', 'active', 'inactive'), `createdAt` (datetime_immutable), `updatedAt` (datetime_immutable, nullable).
    *   Add relationships:
        *   `company` (`ManyToOne` to `Company`, inversedBy="advertisements" - add `advertisements` collection to `Company` entity). Ensure `nullable=false`.
        *   `createdBy` (`ManyToOne` to `Employee`, inversedBy="createdAdvertisements" - add `createdAdvertisements` collection to `Employee` entity). Ensure `nullable=false`.
    *   Add validation constraints (Asserts) for required fields, lengths, etc.
    *   Implement lifecycle callbacks (or use a listener/subscriber) to set `createdAt` and `updatedAt`.
    *   Generate/Update database schema: `make cmd CMD="php bin/console doctrine:migrations:diff"` followed by `make cmd CMD="php bin/console doctrine:migrations:migrate"`.

2.  **Create Repository:**
    *   Create `src/Repository/AdvertisementRepository.php` extending `ServiceEntityRepository`.
    *   Add methods as needed, e.g., `findByCompany(Company $company)` to fetch ads for a specific company.

3.  **Create Form Type:**
    *   Create `src/Form/Backoffice/AdvertisementType.php`.
    *   Add fields corresponding to the `Advertisement` entity (title, description, serviceArea, servicesOffered, status).
    *   Do *not* include `company` or `createdBy` fields directly in the form; these will be set in the controller based on the logged-in user.
    *   Configure field types (e.g., `TextType`, `TextareaType`, `ChoiceType` for status).

4.  **Implement Controller:**
    *   Create `src/Controller/Backoffice/AdvertisementController.php` extending `AbstractController`.
    *   Define route prefix (e.g., `/backoffice/advertisements`).
    *   Implement actions:
        *   `index()`: Fetch advertisements for the current user's company using the repository. Render `templates/backoffice/advertisement/index.html.twig`.
        *   `new(Request $request, EntityManagerInterface $entityManager)`: Create a new `Advertisement` instance. Create and handle the `AdvertisementType` form. On valid submission, set the `company` (based on the logged-in user's association) and `createdBy` (the logged-in user). Persist and flush. Redirect to `index` or `show`. Render `templates/backoffice/advertisement/new.html.twig`.
        *   `show(Advertisement $advertisement)`: (Optional, could be combined with edit or just list view) Display details of a single advertisement. Render `templates/backoffice/advertisement/show.html.twig`. Requires security check (see step 7).
        *   `edit(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager)`: Create and handle the `AdvertisementType` form pre-filled with `$advertisement` data. On valid submission, persist and flush. Redirect to `index` or `show`. Render `templates/backoffice/advertisement/edit.html.twig`. Requires security check.
        *   `delete(Request $request, Advertisement $advertisement, EntityManagerInterface $entityManager)`: Handle deletion, likely via a submitted form with CSRF protection. Remove the entity and flush. Redirect to `index`. Requires security check.

5.  **Create Twig Templates:**
    *   Create the directory `templates/backoffice/advertisement/`.
    *   `index.html.twig`: Extend `backoffice/base.html.twig`. Display a table or list of advertisements fetched in the controller. Include links to `new`, `edit`, `delete`. Use Tailwind CSS classes for styling.
    *   `new.html.twig`: Extend `backoffice/base.html.twig`. Render the form created in the `new` action using `form_start`, `form_widget`, `form_end`. Include a submit button.
    *   `edit.html.twig`: Similar to `new.html.twig`, but for editing.
    *   `_form.html.twig`: (Optional but recommended) Partial template containing the common form fields (`form_row` for each field) included in `new.html.twig` and `edit.html.twig`.
    *   `_delete_form.html.twig`: (Recommended) A small form containing only a CSRF token and a submit button (styled as a link/button) for handling deletions securely. Include this in the `index` or `edit` templates.

6.  **Update Navigation:**
    *   Edit `templates/backoffice/base.html.twig`.
    *   Add a new navigation link in the sidebar pointing to the `index` route of the `AdvertisementController` (e.g., "Moje Ogłoszenia" or "Zarządzaj Ogłoszeniami"). Use Font Awesome icons and Tailwind classes consistent with existing links.

7.  **Implement Security:**
    *   **Route Protection:** Secure the controller routes (e.g., `/backoffice/advertisements/*`) in `config/packages/security.yaml` under `access_control` to require `ROLE_COMPANY_EMPLOYEE` or `ROLE_COMPANY_OWNER`.
    *   **Ownership Check (Voter):**
        *   Create an `AdvertisementVoter` (e.g., `src/Security/Voter/AdvertisementVoter.php`).
        *   Implement logic to check if the logged-in `Employee` belongs to the same `Company` as the `Advertisement` they are trying to view/edit/delete. Check attributes like `VIEW`, `EDIT`, `DELETE`.
        *   Use `#[IsGranted]` attribute or `$this->denyAccessUnlessGranted()` within controller actions (`show`, `edit`, `delete`) to invoke the voter.
    *   **Data Assignment:** In the `new` action, ensure the `company` and `createdBy` fields are set based on the *authenticated user*, not user input. Determine the user's company (this logic might need refinement depending on how `Employee` is linked to `Company` - currently `Employee` has `ownedCompanies` and `employedAt`). Assume for now the user is associated with one primary company for Backoffice actions.

8.  **Testing (Following `backend-guidelines.md`):**
    *   Write basic PHPUnit tests for:
        *   Controller actions (request/response status codes, basic rendering).
        *   Form type validation.
        *   Voter logic.
        *   Repository methods (if custom logic is added).
    *   Execute tests via `make cmd CMD="php vendor/bin/phpunit"`.

9.  **Documentation:**
    *   Add PHPDoc blocks to new classes and methods.
    *   Ensure commit messages are clear and follow project standards.

This plan provides a structured approach to implementing the advertisement feature while adhering to your project's guidelines and architecture. Remember to execute database migrations and clear caches (`make cmd CMD="php bin/console cache:clear"`) as needed during development.
