# Frontend Guidelines for LawetaGO

These guidelines serve as a reference for the AI assistant and development team when creating the frontend for LawetaGO. The project is built on a Symfony backend with Twig as the templating engine. In addition, you are free to integrate modern CSS frameworks (such as Bootstrap, Tailwind CSS) and other frontend libraries as needed. The guidelines below consolidate best practices, project-specific requirements from the PRD, and examples from modern web development.

---

## 1. Key Principles

- **Clarity and Consistency:**  
  Write clear, concise, and technical code. Use descriptive class and variable names to ensure maintainability and easy collaboration.
- **Modularity:**  
  Keep the codebase modular. Use Twig blocks and partials for reusable components and layout structures.
- **Responsiveness:**  
  Ensure all pages and components are responsive. Leverage grid systems and utility classes from frameworks like Bootstrap or Tailwind CSS.
- **Accessibility:**  
  Follow semantic HTML practices, include ARIA attributes where necessary, and design with accessibility in mind.
- **Performance:**  
  Optimize assets (CSS, JavaScript, images) to ensure fast load times and smooth interactions.
- **Separation of Concerns:**  
  Isolate presentation (HTML/CSS) from business logic. Use declarative attributes (e.g., HTMX, if applicable) to enhance interactivity without heavy reliance on custom JavaScript.

---

## 2. Frontend Architecture

- **Symfony + Twig:**
    - Utilize Twig's inheritance and inclusion features to create reusable templates.
    - Organize your templates into logical sections (layout, partials, pages) for clear separation and easy updates.
- **Component-Based Design:**
    - Build UI components (e.g., headers, footers, navigation menus, cards) that can be reused across pages such as the landing page, registration, login, FAQ, etc.
    - Structure CSS and JavaScript so that each component is self-contained.

---

## 3. Frameworks and Libraries

- **CSS Frameworks:**
    - **Bootstrap:**
        - Use Bootstrap’s grid system (container, row, column) for responsive layouts.
        - Leverage Bootstrap components (buttons, modals, alerts) for a consistent UI.
        - Customize Bootstrap’s Sass variables when necessary to align with LawetaGO branding.
    - **Tailwind CSS (Optional):**
        - Use utility classes to rapidly style components without writing custom CSS.
        - Maintain a consistent design system using Tailwind’s configuration.
- **JavaScript Libraries:**
    - Use minimal JavaScript where possible; favor declarative libraries like HTMX to handle interactivity.
    - Integrate any required libraries (e.g., jQuery, Alpine.js) only if they provide clear benefits and do not conflict with Symfony/Twig best practices.

---

## 4. Code Structure and Conventions

- **HTML Markup:**
    - Use semantic HTML5 elements (header, nav, main, footer, section, article) to structure content.
    - Ensure each page has clear and well-documented sections, especially on public-facing pages like the landing page.
- **Twig Templates:**
    - Use Twig blocks and macros for reusable snippets.
    - Follow a clear naming convention for template files and directories.
    - Keep logic minimal in templates; delegate data processing to the controller.
- **CSS & JavaScript:**
    - Organize styles in modular files (or use component-level CSS) and keep custom CSS to a minimum by reusing framework classes.
    - Use descriptive class names that reflect the component’s purpose.
    - Comment your code where necessary to enhance readability.

---

## 5. Responsive Design and Accessibility

- **Responsive Layouts:**
    - Use Bootstrap’s grid system or Tailwind’s responsive utilities to ensure layouts adapt to various screen sizes.
    - Test designs on common resolutions (desktop, tablet, mobile) to guarantee usability.
- **Accessibility:**
    - Use ARIA attributes and proper labels for interactive elements (forms, buttons, navigation links).
    - Ensure color contrasts meet accessibility standards.
    - Structure forms with clear labels, placeholders, and error messages.

---

## 6. Error Handling and Validation

- **Forms and User Inputs:**
    - Implement client-side validation using framework classes (e.g., Bootstrap’s validation states).
    - Provide clear error messages using alert components or inline feedback.
- **Interactive Components:**
    - Use declarative attributes (e.g., HTMX’s hx-get, hx-post) to manage dynamic content loading and error display.
    - Ensure that server errors are gracefully handled with user-friendly messages.

---

## 7. Performance Optimization

- **Asset Management:**
    - Minify CSS and JavaScript files. Use a bundler (Webpack Encore for Symfony) to manage dependencies.
    - Leverage CDNs for serving frameworks (Bootstrap, Tailwind CSS) to improve load times.
- **Efficient Templates:**
    - Render only necessary HTML fragments, especially for pages with dynamic updates.
    - Use caching strategies on the server and client sides where appropriate.

---

## 8. Integration with Symfony and Twig

- **Template Inheritance:**
    - Create a base layout (base.html.twig) that includes common elements (header, footer, navigation).
    - Extend the base layout in all page-specific templates.
- **Asset Inclusion:**
    - Use Symfony’s asset management functions to include CSS, JavaScript, and images.
    - Organize assets logically and maintain clear documentation on how assets are built and served.
- **Form Rendering:**
    - Utilize Symfony’s form component with Twig’s form helpers for consistency and ease of maintenance.

---

## 9. Additional Considerations

- **Landing Page Specifics:**
    - The landing page should include clear sections (Hero, About, Features, Testimonials, CTA, Footer) as detailed in the PRD.
    - Ensure that public-facing pages (registration, login, FAQ, contact, privacy policy, terms) are fully accessible without authentication.
- **Navigation to B2C:**
    - Include a prominent button or link in the header and footer that redirects users to the LawetaGO B2C application.
- **Modularity and Reusability:**
    - Design components that can be easily reused across multiple pages.
    - Keep the style consistent with the LawetaGO brand guidelines (colors, typography, spacing).

---

## 10. Key Conventions

1. **Naming:**
    - Follow framework conventions for class names (e.g., Bootstrap’s naming conventions or Tailwind’s utility classes).
    - Use clear and descriptive names for custom classes and Twig blocks.
2. **Documentation:**
    - Comment your code and document the purpose of components.
    - Maintain a consistent file and folder structure.
3. **Collaboration:**
    - Ensure code is formatted consistently (consider using Prettier, ESLint, or similar tools).
    - Keep dependencies and versioning documented for ease of maintenance.

---

## 11. Dependencies

- **Backend:** Symfony (latest version), Twig.
- **CSS Frameworks:** Bootstrap (latest version) and/or Tailwind CSS.
- **JavaScript (Optional):** HTMX for enhanced interactivity, with minimal additional JavaScript.
- **Build Tools:** Webpack Encore (for asset management) or similar bundlers.
- **CDN:** Use CDN links for third-party libraries to improve performance and caching.

---

Refer to the official documentation of Symfony, Twig, Bootstrap, Tailwind CSS, and HTMX for further examples and detailed usage patterns. These guidelines are designed to help maintain consistency, ensure performance, and provide a seamless user experience across the LawetaGO frontend.
