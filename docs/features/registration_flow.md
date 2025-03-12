# Registration Page Specifications for LawetaGO

This document provides a precise description of the registration page for LawetaGO’s admin panel, outlines the user flow for the registration process, and presents a plan for implementation. This document is intended for the AI assistant and development team to ensure that the registration functionality is built consistently and according to requirements.

---

## 1. Registration Page – Detailed Description

### Page Title and Introduction
- **Title:** "Rejestracja Firmy"
- **Subtitle/Description:**  
  A brief explanation that new companies can register to gain access to the LawetaGO admin panel.  
  _Example text:_ "Zarejestruj swoją firmę, aby zarządzać oddziałami, zleceniami i ofertą usług. Twoje konto zostanie zweryfikowane przez administratora przed aktywacją."

### Form Fields and UI Elements

1. **Nazwa firmy (Company Name):**
   - **Type:** Text input
   - **Placeholder:** "Wpisz nazwę firmy"
   - **Validation:** Required; non-empty string.

2. **Adres siedziby (Company Address):**
   - **Type:** Text input
   - **Placeholder:** "Wpisz adres siedziby"
   - **Validation:** Required; must be a valid address format.

3. **NIP (Tax Identification Number):**
   - **Type:** Text or number input
   - **Placeholder:** "Wpisz NIP (10 cyfr)"
   - **Validation:**  
     - Required  
     - Must consist of exactly 10 digits  
     - Must be unique (check against the database)

4. **Dane kontaktowe (Contact Details):**
   - **Telefon (Phone):**
     - **Type:** Text input
     - **Placeholder:** "Wpisz numer telefonu"
     - **Validation:** Required; proper phone number format.
   - **Email:**
     - **Type:** Email input
     - **Placeholder:** "Wpisz adres email"
     - **Validation:** Required; must be a valid email address.

5. **Hasło (Password):**
   - **Type:** Password input
   - **Placeholder:** "Wpisz hasło"
   - **Validation:**  
     - Required  
     - Must meet security requirements (minimum length, character complexity, etc.)  
     - Use both client-side (e.g., Bootstrap validation states) and server-side Symfony constraints.

6. **Akceptacja regulaminu (Accept Terms and Conditions):**
   - **Type:** Checkbox
   - **Label:** "Akceptuję regulamin"
   - **Validation:** Required; must be checked before submission.
   - **Additional:** Provide a link to the detailed "Regulamin".

### Action Buttons and Feedback

- **Primary CTA Button:**  
  - Label: "Zarejestruj"
  - Behavior: Submits the form.
- **Feedback Messages:**  
  - Display inline error messages for each field when validation fails.
  - On successful submission, show a message indicating that the registration is under review (e.g., "Twoja rejestracja została przyjęta. Po weryfikacji przez administratora otrzymasz potwierdzenie na email.").

---

## 2. User Flow for Registration

The registration process consists of the following steps:

1. **Navigation to Registration Page:**
   - The user (future company owner) accesses the registration page via a public link (e.g., from the landing page or the "Rejestracja" menu item).

2. **Form Filling:**
   - The user enters all required data into the registration form:
     - Company Name, Company Address, NIP, Phone, Email, Password.
     - Checks the “Akceptacja regulaminu” checkbox.
   - Client-side validations are triggered as the user interacts with the form (e.g., checking email format, ensuring the NIP is 10 digits).

3. **Form Submission:**
   - Upon clicking the "Zarejestruj" button:
     - The form data is sent to the backend.
     - Server-side validations occur, including checking the uniqueness of the NIP and enforcing password policies.
     - If any errors are found, error messages are returned and displayed inline on the form.

4. **Registration Record Creation:**
   - If validations pass, the system creates a new company record with a status of "oczekująca na weryfikację".
   - A confirmation email is sent to the user informing them that their registration is pending administrator verification.
   - A notification is sent to the administrator for manual verification of the new registration.

5. **Administrator Verification:**
   - The administrator reviews the submitted registration details.
   - After manual verification, the administrator updates the company status to "aktywna".
   - The user receives an email notification indicating that the account is now active.

6. **Post-Registration:**
   - The user can now log in to the admin panel and access full functionalities according to their role (Właściciel).

---

## 3. Implementation Plan

### Step 1: Design and Wireframing
- **Wireframe Creation:**
  - Sketch the layout of the registration page including header, form fields, CTA button, and feedback areas.
- **Review with Stakeholders:**
  - Confirm the design meets the business and user requirements outlined in the PRD.

### Step 2: Frontend Development
- **Twig Template:**
  - Create a new Twig template (e.g., `registration.html.twig`) in the appropriate module (BackOffice).
  - Use Twig blocks and inheritance (extend from a base layout) to maintain consistency.
- **Form Markup:**
  - Develop the HTML form with all required fields and labels.
  - Integrate client-side validation using Bootstrap classes (or Tailwind CSS utilities if preferred).
- **Accessibility and Responsiveness:**
  - Ensure the form is responsive and accessible on different devices and meets ARIA guidelines.

### Step 3: Backend Development
- **Symfony Controller:**
  - Create a new controller (e.g., `RegistrationController`) with actions for displaying the form and processing submissions.
- **Form Type:**
  - Develop a Symfony Form Type (e.g., `CompanyRegistrationType`) that includes:
    - Fields: companyName, address, NIP, phone, email, password, acceptTerms.
    - Validation constraints: NotBlank, Regex (for NIP), Email, Length and Custom Password constraints.
- **Handling Submission:**
  - In the controller, handle form submission:
    - Validate the form.
    - Check for the uniqueness of the NIP.
    - Save the new company record with the status "oczekująca na weryfikację".
- **Email Notifications:**
  - Use Symfony Mailer to send notification emails to both the user and the administrator.
- **Security:**
  - Sanitize and validate all inputs.
  - Ensure that sensitive data (e.g., passwords) are properly hashed before storage.

### Step 4: Testing
- **Unit and Integration Tests:**
  - Write PHPUnit tests for the form processing logic.
  - Test validations, database insertion, and email notifications.
- **Manual Testing:**
  - Test the registration process in a local Docker environment using the Makefile:
    ```bash
    make cmd CMD="php bin/console cache:clear"
    make cmd CMD="php vendor/bin/phpunit"
    ```
  
### Step 5: Deployment and Monitoring
- **Docker Deployment:**
  - Ensure that the registration page is integrated into the Docker container.
  - Update the Docker configuration if necessary.
- **Monitoring:**
  - Set up logging (using Monolog) to capture errors during the registration process.
  - Monitor the application after deployment for any issues.

---

By following these detailed specifications, user flow steps, and the implementation plan, the registration page for LawetaGO will provide a seamless and secure experience for new company registrations. This document should serve as the basis for the AI assistant to generate code and assist in the development of this functionality.