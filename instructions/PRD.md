# Product Requirements Document (PRD) - Panel Administracyjny LawetaGO (B2B - Dla Właścicieli i Pracowników)

## 1. Wprowadzenie (Introduction/Purpose)

**Cel dokumentu:**

Niniejszy dokument Product Requirements Document (PRD) szczegółowo definiuje wymagania dla **panelu administracyjnego aplikacji LawetaGO (B2B)**, przeznaczonego dla właścicieli firm lawetowych oraz ich pracowników. Dokument ten stanowi kompleksowe źródło informacji dla zespołu projektowego, opisując funkcjonalności, cele i wymagania techniczne panelu administracyjnego. Jego celem jest zapewnienie spójnego zrozumienia zakresu projektu i ułatwienie procesu developmentu.

**Przeznaczenie Panelu Administracyjnego:**

Panel administracyjny LawetaGO (B2B) ma być centralnym narzędziem dla właścicieli firm lawetowych i ich pracowników do **zarządzania ich obecnością i operacjami w aplikacji LawetaGO**. Panel umożliwi:
* Zarządzanie profilami firmy i oddziałów,
* Zarządzanie użytkownikami – system ról obejmuje **Właściciela** (pełny dostęp) oraz **Pracownika** (dostęp ograniczony),
* Zarządzanie usługami, cennikami, dostępnością,
* Uproszczone zarządzanie zleceniami (w MVP),
* Przeglądanie ocen i recenzji.

W nowym modelu ról **Pracownik** jest przypisywany do całej firmy i ma wgląd do danych ze wszystkich oddziałów, choć jego uprawnienia ograniczają się do obsługi zleceń, zarządzania ofertą usług, cennikiem oraz przeglądania ocen i recenzji.

## 2. Cele i Obiektywy (Goals and Objectives) - B2B

**Główny cel dla właścicieli lawet (B2B):**

Umożliwienie właścicielom firm lawetowych **efektywnego zarządzania ich biznesem w ramach platformy LawetaGO**, zwiększenie ich **widoczności dla klientów**, optymalizacja **procesu przyjmowania i realizacji zleceń** oraz budowanie **pozytywnej reputacji** w aplikacji.

**Szczegółowe cele dla właścicieli lawet (B2B):**

* **Łatwa rejestracja i konfiguracja profilu firmy:** Umożliwienie szybkiego i intuicyjnego zarejestrowania firmy w aplikacji oraz skonfigurowania profilu firmy i oddziałów.
* **Efektywne zarządzanie oddziałami firmy:** Umożliwienie właścicielom firm zarządzania strukturą firmy poprzez dodawanie, edycję i usuwanie oddziałów.
* **Kontrola dostępu i uprawnień:** Zapewnienie mechanizmu kontroli dostępu do panelu administracyjnego poprzez system ról.
    - **Właściciel** ma pełny dostęp do wszystkich funkcjonalności.
    - **Pracownik** – przypisany do firmy, ma dostęp do obsługi zleceń, ofert, cennika oraz przeglądania ocen i recenzji, przy czym widzi dane ze wszystkich oddziałów.
* **Prezentacja oferty usług:** Umożliwienie firmom lawetowym prezentacji ich oferty usług, typów lawet, cenników i obszaru działania w atrakcyjny i przejrzysty sposób.
* **Uproszczone zarządzanie zleceniami (MVP):** Umożliwienie firmom lawetowym przyjmowania, odrzucania oraz aktualizacji statusu zleceń.
    - **Właściciel** ma pełny dostęp,
    - **Pracownik** zarządza zleceniami firmy, widząc dane ze wszystkich oddziałów.
* **Zarządzanie dostępnością lawet:** Umożliwienie firmom lawetowym informowania o dostępności lawet i planowania grafików pracy (w podstawowym zakresie w MVP).
* **Budowanie reputacji:** Umożliwienie firmom lawetowym budowania pozytywnej reputacji poprzez zbieranie ocen i recenzji od zadowolonych klientów.
* **Dostęp do pomocy i wsparcia:** Zapewnienie dostępu do sekcji pomocy i wsparcia technicznego w razie problemów z panelem administracyjnym.

## 3. Grupa Docelowa (Target Audience) - B2B

**Charakterystyka użytkowników panelu administracyjnego (B2B):**

* **Właściciele Firm Lawetowych:**
    * Osoby zarządzające firmami lawetowymi w Polsce.
    * Posiadają wiedzę z zakresu branży pomocy drogowej i lawetowania.
    * Odpowiedzialni za rozwój biznesu, marketing i relacje z klientami.
    * Oczekują narzędzi, które pomogą im w efektywnym zarządzaniu firmą oraz pozyskiwaniu nowych klientów.
    * Mają pełny dostęp do wszystkich funkcjonalności panelu administracyjnego.
* **Pracownicy Firm Lawetowych:**
    * Osoby zatrudnione w firmach lawetowych, odpowiedzialne za bieżącą obsługę operacyjną.
    * Są przypisywani do firmy i mają dostęp do danych ze wszystkich oddziałów, co umożliwia im kompleksową obsługę zleceń, ofert, cennika oraz przeglądanie ocen i recenzji.
    * Nie mają uprawnień do zarządzania danymi firmy ani oddziałami.
    * Oczekują intuicyjnego interfejsu umożliwiającego szybką i skuteczną obsługę przydzielonych zadań.

## 4. Zakres Projektu (Scope) - B2B (Panel Administracyjny)

**Funkcjonalności zawarte w projekcie (In-Scope) - Panel Administracyjny (B2B):**

* **Zarządzanie Firmą i Oddziałami:**
    * Rejestracja Firmy (wraz z manualną weryfikacją przez administratora platformy).
    * Logowanie i Wylogowanie.
    * Edycja Profilu Firmy.
    * Zarządzanie Oddziałami Firmy (dodawanie, edycja, usuwanie oddziałów) – dostępne **wyłącznie dla Właściciela**.
    * Zarządzanie Kontami Użytkowników Firmy i Oddziałów (dodawanie, edycja, usuwanie użytkowników, przypisywanie ról: Właściciel, Pracownik).
* **Zarządzanie Usługami i Cennikiem:**
    * Definiowanie Rodzajów Lawet.
    * Ustalanie Cennika (na poziomie firmy, wspólny dla oddziałów w MVP).
    * Zarządzanie Obszarem Działania oddziału firmy.
* **Zarządzanie Dostępnością i Grafikiem:**
    * Kalendarz Dostępności zarządzany dla oddziału firmy.
* **Uproszczone Zarządzanie Zleceniami (MVP):**
    * Lista Zleceń (filtrowanie, sortowanie, wyszukiwanie).
        - **Właściciel** ma dostęp do pełnej listy zleceń firmy (bez względu na oddziały).
        - **Pracownik**, przypisany do firmy, widzi zlecenia obejmujące dane ze wszystkich oddziałów.
    * Szczegóły Zlecenia (uproszczony widok).
    * Zmiana Statusu Zlecenia (ograniczony zestaw statusów: "Przyjęte", "W drodze", "Zakończone", "Anulowane").
* **Oceny i Recenzje:**
    * Przeglądanie Ocen i Recenzji (dla firmy).
* **Pomoc/FAQ:**
    * Sekcja Pomocy/FAQ dla panelu administracyjnego.

**Funkcjonalności wyłączone z projektu (Out-of-Scope) - Panel Administracyjny (B2B - MVP):**

* Zaawansowane Statystyki i Raporty (przesunięte na kolejne wersje).
* Zarządzanie Grafikiem Kierowców (opcjonalne w MVP, rozważyć w przyszłości).
* Elastyczne ustawianie cenników specyficznych dla oddziałów (w MVP cennik wspólny dla firmy).
* Zaawansowane funkcje zarządzania zleceniami (np. automatyczne przypisywanie zleceń, optymalizacja tras, rozbudowane statusy zleceń, fakturowanie, integracja z systemami zewnętrznymi – poza zakresem MVP).
* Możliwość odpowiedzi na recenzje klientów (opcjonalne w MVP, rozważyć w przyszłości).
* Wsparcie wielojęzyczne panelu administracyjnego (w MVP język polski).

## 5. Wymagania Funkcjonalne (Functional Requirements) - Panel Administracyjny (B2B)

**(Szczegółowy opis wybranych funkcjonalności – przykłady; należy rozbudować dla wszystkich funkcjonalności B2B panelu administracyjnego wymienionych w sekcji "In-Scope")**

**5.1. Rejestracja Firmy:**

* **Cel funkcjonalności:** Umożliwienie właścicielom firm lawetowych zarejestrowania swojej firmy w aplikacji LawetaGO i rozpoczęcia korzystania z panelu administracyjnego.
* **Kroki interakcji użytkownika (User Flow):**
    1. Użytkownik (przyszły właściciel firmy) przechodzi na stronę rejestracji panelu administracyjnego.
    2. Użytkownik wypełnia formularz rejestracyjny, podając dane firmy (Nazwa firmy, adres siedziby, NIP, dane kontaktowe, hasło) oraz akceptuje regulamin.
    3. Użytkownik klika przycisk "Zarejestruj".
    4. Aplikacja zapisuje dane firmy w bazie danych ze statusem "oczekująca na weryfikację". Następuje wysłanie maila z informacją o weryfikacji do użytkownika, który rejestrował konto.
    5. Administrator platformy LawetaGO otrzymuje powiadomienie o nowej rejestracji firmy.
    6. Administrator manualnie weryfikuje dane firmy (np. sprawdzając NIP w bazie danych, kontaktując się z firmą).
    7. Po pozytywnej weryfikacji administrator zmienia status firmy na "aktywna".
    8. Właściciel firmy otrzymuje powiadomienie email o pozytywnej weryfikacji i aktywacji konta.
    9. Właściciel firmy może zalogować się do panelu administracyjnego.
* **Dane wejściowe i wyjściowe:**
    * **Wejście:** Nazwa firmy, adres siedziby, NIP, dane kontaktowe (telefon, email), hasło, akceptacja regulaminu.
    * **Wyjście:** Zarejestrowana firma w bazie danych ze statusem "oczekująca na weryfikację", powiadomienie dla administratora oraz rejestrującego konto.
* **Reguły biznesowe:**
    * NIP firmy musi być unikalny w systemie.
    * Hasło musi spełniać minimalne wymagania bezpieczeństwa.
    * Rejestracja firmy wymaga manualnej weryfikacji przez administratora platformy.
    * Firma pozostaje nieaktywna i niedostępna w aplikacji B2B do czasu pozytywnej weryfikacji.
* **Przykłady użycia (Use Cases):**
    * Właściciel nowej firmy lawetowej rejestruje firmę w aplikacji LawetaGO, aby móc oferować usługi lawetowania za pośrednictwem platformy.
    * Administrator platformy weryfikuje dane firmy i aktywuje jej konto, umożliwiając firmie korzystanie z panelu administracyjnego.

**5.2. Zarządzanie Oddziałami Firmy:**

* **Cel funkcjonalności:** Umożliwienie właścicielom firm lawetowych zarządzania strukturą firmy poprzez dodawanie, edycję i usuwanie oddziałów, co jest istotne dla firm działających w wielu lokalizacjach.
* **Kroki interakcji użytkownika (User Flow):**
    1. Właściciel firmy loguje się do panelu administracyjnego.
    2. W menu nawigacyjnym wybiera sekcję "Oddziały".
    3. Użytkownik widzi listę istniejących oddziałów firmy (jeśli istnieją).
    4. **Dodawanie Oddziału:**
        * Użytkownik klika przycisk "Dodaj Oddział".
        * Wyświetla się formularz dodawania oddziału.
        * Użytkownik wypełnia formularz (Nazwa oddziału, adres oddziału, dane kontaktowe oddziału, opcjonalnie opis).
        * Użytkownik klika przycisk "Zapisz".
        * Nowy oddział zostaje dodany do listy oddziałów firmy.
    5. **Edycja Oddziału:**
        * Właściciel wybiera oddział z listy, który chce edytować.
        * Użytkownik klika przycisk "Edytuj" przy wybranym oddziale.
        * Wyświetla się formularz edycji oddziału, wypełniony aktualnymi danymi.
        * Użytkownik edytuje dane oddziału.
        * Użytkownik klika przycisk "Zapisz".
        * Zmiany w oddziale zostają zapisane.
    6. **Usuwanie Oddziału:**
        * Właściciel wybiera oddział z listy, który chce usunąć.
        * Użytkownik klika przycisk "Usuń" przy wybranym oddziale.
        * Wyświetla się okno dialogowe z potwierdzeniem usunięcia oddziału.
        * Użytkownik potwierdza usunięcie oddziału.
        * Oddział zostaje usunięty z listy.
* **Dane wejściowe i wyjściowe:**
    * **Wejście (Dodawanie):** Nazwa oddziału, adres oddziału, dane kontaktowe oddziału, opis oddziału (opcjonalnie).
    * **Wejście (Edycja):** Identyfikator oddziału, edytowane dane oddziału.
    * **Wejście (Usuwanie):** Identyfikator oddziału.
    * **Wyjście:** Lista oddziałów firmy, potwierdzenie dodania, edycji lub usunięcia oddziału.
* **Reguły biznesowe:**
    * Nazwa oddziału musi być unikalna w ramach firmy.
    * Usunięcie oddziału wymaga potwierdzenia.
    * Operacje na oddziałach są dostępne **wyłącznie dla Właściciela**.
* **Przykłady użycia (Use Cases):**
    * Właściciel firmy lawetowej, która rozszerza działalność na nowe miasto, dodaje nowy oddział firmy w panelu administracyjnym.
    * Właściciel firmy aktualizuje adres lub dane kontaktowe istniejącego oddziału.
    * Właściciel firmy zamyka oddział i usuwa go z panelu administracyjnego.

**5.3. Uproszczone Zarządzanie Zleceniami (MVP):**

* **Cel funkcjonalności:** Umożliwienie sprawnej obsługi zleceń przez firmę lawetową.
* **Kroki interakcji użytkownika (User Flow):**
    1. Użytkownik loguje się do panelu administracyjnego.
    2. W menu wybiera sekcję "Zlecenia".
    3. **Widok Zleceń:**
        * **Właściciel** ma dostęp do pełnej listy zleceń firmy (bez względu na oddziały).
        * **Pracownik**, przypisany do firmy, widzi zlecenia obejmujące dane ze wszystkich oddziałów.
    4. Użytkownik może filtrować, sortować oraz wyszukiwać zlecenia.
    5. Po wybraniu zlecenia wyświetlane są szczegóły oraz opcje zmiany statusu (dostępne: "Przyjęte", "W drodze", "Zakończone", "Anulowane").
* **Dane wejściowe i wyjściowe:**
    * **Wejście:** Parametry wyszukiwania, identyfikator zlecenia, dane dotyczące zmiany statusu.
    * **Wyjście:** Lista zleceń, szczegóły wybranego zlecenia, potwierdzenie zmiany statusu.
* **Reguły biznesowe:**
    * Zmiana statusu zlecenia musi być zgodna z przyjętym zestawem statusów.
    * **Pracownik** ma uprawnienia jedynie do obsługi zleceń (przyjmowania, zmiany statusów) – nie ma możliwości modyfikacji danych firmy ani oddziałów.
* **Przykłady użycia (Use Cases):**
    * Właściciel przegląda wszystkie zlecenia firmy i zarządza nimi.
    * Pracownik obsługuje przydzielone zlecenia, zmieniając ich status w zależności od postępu realizacji.

*(Dalsze funkcjonalności należy rozbudować analogicznie, pamiętając o rozróżnieniu uprawnień: operacje dotyczące firmy i oddziałów dostępne są tylko dla Właściciela, natomiast obsługa zleceń, ofert, cennika oraz przeglądanie ocen i recenzji – dla Pracownika, który widzi dane ze wszystkich oddziałów.)*

## 6. Wymagania Niefunkcjonalne (Non-Functional Requirements) - Panel Administracyjny (B2B)

* **Wydajność (Performance):**
    * Czas ładowania strony panelu: Maksymalnie 3 sekundy.
    * Czas odpowiedzi serwera na operacje CRUD: Maksymalnie 2 sekundy.
    * Panel powinien działać płynnie i responsywnie.
    * Skalowalność umożliwiająca obsługę przewidywanej liczby firm i użytkowników.
* **Bezpieczeństwo (Security):**
    * Dane firm i użytkowników przechowywane w zaszyfrowanej bazie danych.
    * Komunikacja szyfrowana (HTTPS).
    * Zabezpieczenia przed atakami (np. SQL Injection, Cross-Site Scripting, Broken Authentication).
    * System autentykacji i autoryzacji oparty na rolach (Symfony Security) musi być poprawnie zaimplementowany.
    * Zgodność z RODO.
    * Regularne audyty bezpieczeństwa.
* **Użyteczność (Usability):**
    * Intuicyjny, przejrzysty i ergonomiczny interfejs.
    * Logiczna nawigacja w panelu administracyjnym.
    * Czytelne formularze z odpowiednimi walidacjami.
    * Responsywność dostosowana do różnych rozdzielczości ekranów.
    * Dostępność w języku polskim.
* **Niezawodność (Reliability):**
    * Dostępność 24/7 z uptime na poziomie co najmniej 99.9%.
    * Minimalizacja błędów i awarii.
    * Czytelne komunikaty w przypadku wystąpienia błędów.
    * Regularne backupy danych.
* **Kompatybilność (Compatibility):**
    * Kompatybilność z popularnymi przeglądarkami (Chrome, Firefox, Safari, Edge).
    * Responsywny design.
* **Lokalizacja i Internacjonalizacja (Localization & Internationalization):**
    * W MVP panel administracyjny dostępny w języku polskim.
    * Obsługa waluty PLN.
    * Strefa czasowa CET.
    * Możliwość rozszerzenia o inne języki w przyszłości.

## 7. Wymagania Techniczne (Technical Requirements) - Panel Administracyjny (B2B)

* **Platforma:** Webowa aplikacja dostępna przez przeglądarkę internetową.
* **Technologie i narzędzia:**
    * **Frontend:** Twig (silnik szablonów Symfony), HTML, CSS, JavaScript (minimalne użycie, głównie dla interakcji UI).
    * **Backend:** Symfony (PHP Framework).
    * **Baza danych:** PostgreSQL.
    * **Serwer:** Nginx i PHP-FPM.
    * **Testowanie:** PHPUnit.
    * **Bezpieczeństwo:** Symfony Security Component, zabezpieczenia serwera i bazy danych według najlepszych praktyk.
* **Integracje z zewnętrznymi systemami:**
    * **Brak integracji** w MVP; potencjalne integracje (np. systemy płatności, księgowe, API map) rozważone w przyszłych wersjach.
* **Architektura aplikacji:**
    * Architektura MVC oparta na Symfony.
    * Komunikacja z bazą danych poprzez Doctrine ORM.
* **Wymagania dotyczące serwera:**
    * Hosting w chmurze lub na dedykowanym serwerze.
    * Wydajność serwera dostosowana do przewidywanego obciążenia.
    * Bezpieczeństwo serwera (ochrona przed atakami, regularne aktualizacje, monitoring).
    * Całodobowa dostępność serwera.

## 8. Przyszłe Kierunki Rozwoju (Future Considerations/Roadmap) - Panel Administracyjny (B2B)

* **Zaawansowane Statystyki i Raporty:** Rozbudowane statystyki i raporty dotyczące zleceń, przychodów, popularności usług, ocen firm itp. z możliwością generowania raportów (PDF, CSV, Excel).
* **Zarządzanie Grafikiem Kierowców:** Rozbudowa funkcjonalności zarządzania grafikami pracy (planowanie dyżurów, urlopów, integracja z kalendarzami).
* **Elastyczne Cenniki Oddziałów:** Możliwość definiowania cenników specyficznych dla poszczególnych oddziałów.
* **Zarządzanie Obszarami Działania Oddziałów:** Umożliwienie definiowania specyficznych obszarów działania dla oddziałów.
* **Zaawansowane Zarządzanie Zleceniami:** Rozbudowa funkcjonalności w zakresie automatycznego przypisywania zleceń, optymalizacji tras, rozbudowanych statusów, fakturowania oraz integracji z systemami zewnętrznymi.
* **Marketing i Promocja Firm:** Dodanie narzędzi marketingowych, np. możliwość tworzenia promocji, zarządzania reklamami, analizy efektywności działań marketingowych.
* **Wsparcie Wielojęzyczne Panelu Administracyjnego:** Rozszerzenie o dodatkowe języki (np. angielski) w kolejnych wersjach.

## 9. Kwestie Otwarte / Pytania (Open Issues/Questions) - Panel Administracyjny (B2B)

* **Szczegółowy model ról i uprawnień:**  
  Model ról w systemie zostaje uproszczony do dwóch podstawowych:
    - **Właściciel:** Pełny dostęp do wszystkich funkcjonalności (zarządzanie firmą, oddziałami, użytkownikami, usługami, zleceniami, itd.).
    - **Pracownik:** Uprawnienia ograniczone do obsługi zleceń, ofert, cennika, ocen i recenzji – brak możliwości zarządzania firmą i oddziałami. Pracownik jest przypisywany do firmy i widzi dane ze wszystkich oddziałów.
* **Proces weryfikacji firm lawetowych:** Doprecyzowanie procesu manualnej weryfikacji (kryteria, wymagane dokumenty, czas weryfikacji).
* **Model cennika:** Ustalenie, czy cennik w MVP jest całkowicie dowolny dla firm, czy platforma narzuca pewne ograniczenia/sugerowane ceny, oraz sposób prezentacji cennika w aplikacji B2C.
* **Obsługa klienta B2B:** Organizacja wsparcia dla właścicieli firm lawetowych korzystających z panelu administracyjnego (FAQ, email, telefon, dedykowany support).
* **Wybór bazy danych:** Ostateczna decyzja co do systemu bazodanowego (PostgreSQL, MySQL, inne) na podstawie analizy wymagań i preferencji zespołu.
* **Szczegóły integracji map (opcjonalne):** Określenie, czy w MVP będzie wymagana integracja map (np. wizualizacja zleceń, obszarów działania) oraz wybór API mapowego.

---
