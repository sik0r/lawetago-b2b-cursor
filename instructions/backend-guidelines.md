# Backend Guidelines for LawetaGO

These guidelines are intended to help the AI assistant and the development team create new backend functionalities for LawetaGO. They outline best practices, coding conventions, and technical requirements. The application is built using PHP 8.3 with Symfony 7+ and follows the PER-CS 2.0.0 code style. It runs within a Docker container, and all CLI commands should be executed via the provided Makefile (using the pattern `make cmd CMD="..."`).

---

## 1. Environment and Tools

- **PHP Version:** PHP 8.3
- **Framework:** Symfony 7+
    - Utilize Symfony’s MVC architecture and best practices.
- **Code Style:** PER-CS 2.0.0
    - Follow the specified coding standards and formatting rules.
- **Containerization:**
    - The application runs in a Docker container.
    - Use Docker Compose (if applicable) to manage multi-container setups.
- **CLI Execution:**
    - All CLI commands must be executed using the Makefile.
    - Example usage:
      ```bash
      make cmd CMD="php bin/console cache:clear"
      ```
- **Testing:**
    - Use PHPUnit for unit and integration tests.
- **Dependency Management:**
    - Use Composer for managing PHP dependencies.
- **Version Control:**
    - Maintain a clean Git history with feature branches, code reviews, and commit messages that follow best practices.

---

## 2. Key Principles

- **Maintainability and Readability:**
    - Write clean, well-documented code following PER-CS 2.0.0.
    - Keep business logic out of controllers; use services and repositories.
- **Modularity and Reusability:**
    - Structure code into logical bundles or modules (e.g., BackOffice, Shared, CustomerApi).
    - Use dependency injection to decouple components.
- **Testability:**
    - Ensure code is testable with PHPUnit.
    - Write unit tests and integration tests for all new functionality.
- **Performance:**
    - Optimize queries, cache results where appropriate, and follow Symfony best practices for performance.
- **Security:**
    - Adhere to Symfony Security Component best practices.
    - Sanitize inputs and validate data rigorously.
- **Error Handling and Logging:**
    - Use Symfony’s logging (Monolog) to record errors and important events.
    - Handle exceptions gracefully and provide meaningful error messages.

---

## 3. Application Architecture

- **MVC Structure:**
    - Follow Symfony’s MVC architecture. Organize code into controllers, services, repositories, entities, and views (Twig templates).
- **Modular Design:**
    - Split functionality into distinct modules (e.g., BackOffice for the admin panel).
    - Use Symfony bundles or a modular folder structure under `src/` for separation of concerns.
- **Service Layer:**
    - Encapsulate business logic in services.
    - Utilize dependency injection to manage service dependencies.
- **Repository Pattern:**
    - Use Doctrine ORM for database interactions.
    - Create custom repository classes for complex queries.

---

## 4. Coding Conventions and Best Practices

- **Code Style (PER-CS 2.0.0):**
    - Follow the PER-CS 2.0.0 coding standard for naming conventions, file structure, and code formatting.
    - Use strict typing and declare return types wherever possible.
- **Documentation:**
    - Document all classes, methods, and services using PHPDoc.
    - Keep inline comments concise and relevant.
- **Error Handling:**
    - Use try-catch blocks to manage exceptions.
    - Return standardized error responses for API endpoints.
- **Security Best Practices:**
    - Validate and sanitize all user inputs.
    - Use Symfony’s built-in security features (voters, access control, etc.).
- **Testing and Quality Assurance:**
    - Write unit tests for all new components using PHPUnit.
    - Follow TDD (Test Driven Development) principles when possible.
    - Ensure code coverage meets project standards.

---

## 5. Docker and Environment Configuration

- **Docker Container:**
    - The application runs inside a Docker container.
    - Use a `Dockerfile` to build the application image and `docker-compose.yml` (if applicable) to manage services (e.g., PHP, Nginx, PostgreSQL).
- **Configuration Management:**
    - Use environment variables for configuration (e.g., database credentials, API keys).
    - Document required environment variables in a `.env.example` file.
- **CLI via Makefile:**
    - Execute all command-line tasks using the provided Makefile.
    - Example commands:
        - Clear cache:
          ```bash
          make cmd CMD="php bin/console cache:clear"
          ```
        - Run tests:
          ```bash
          make cmd CMD="php vendor/bin/phpunit"
          ```
        - Run migrations:
          ```bash
          make cmd CMD="php bin/console doctrine:migrations:migrate"
          ```

---

## 6. Database and ORM

- **Database:**
    - Use PostgreSQL as the database.
- **Doctrine ORM:**
    - Use Doctrine for all database interactions.
    - Organize entity classes within their respective modules.
    - Write efficient DQL queries and leverage Doctrine’s caching strategies.

---

## 7. Command Line Interface (CLI) Guidelines

- **Makefile Usage:**
    - All CLI tasks should be executed via the Makefile.
    - Provide clear documentation and examples for each common task.
- **Symfony Console:**
    - Use Symfony Console commands to manage tasks (e.g., migrations, cache clear, custom commands).
    - Register custom commands in the appropriate module.

---

## 8. Performance and Optimization

- **Caching:**
    - Utilize Symfony’s caching mechanisms (HTTP caching, Doctrine caching, etc.) where appropriate.
- **Profiling and Debugging:**
    - Use Symfony’s built-in profiler to identify performance bottlenecks.
- **Efficient Code:**
    - Optimize database queries and avoid N+1 query problems.
    - Profile PHP code using Xdebug or similar tools in the Docker environment.

---

## 9. Additional Considerations

- **Continuous Integration/Deployment:**
    - Integrate CI/CD pipelines to run tests and linting on every commit.
    - Automate deployments using Docker and version control triggers.
- **Logging and Monitoring:**
    - Implement logging via Monolog.
    - Set up monitoring for server performance and error tracking.
- **API Documentation:**
    - Document REST API endpoints using tools such as Swagger or API Platform (if applicable).

---

## 10. Summary of Dependencies

- **PHP:** 8.3
- **Framework:** Symfony 7+
- **ORM:** Doctrine ORM
- **Testing:** PHPUnit
- **Code Style:** PER-CS 2.0.0
- **Containerization:** Docker
- **CLI Execution:** Makefile (`make cmd CMD="..."`)
- **Additional Libraries:**
    - Use Composer for dependency management.
    - Optional libraries for logging, caching, etc., as per project needs.

---

Refer to the official documentation for PHP, Symfony, Doctrine, and Docker for more detailed examples and usage patterns. These guidelines aim to ensure consistency, maintainability, and high performance across the LawetaGO backend.
