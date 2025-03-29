**1. Zarządzanie Oddziałami Firmy:**

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