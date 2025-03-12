<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CompanyRegistrationDto
{
    #[Assert\NotBlank(message: 'Nazwa firmy jest wymagana')]
    public string $name;

    #[Assert\NotBlank(message: 'Adres siedziby jest wymagany')]
    public string $address;

    #[Assert\NotBlank(message: 'NIP jest wymagany')]
    #[Assert\Length(exactly: 10, exactMessage: 'NIP musi składać się z dokładnie 10 cyfr')]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'NIP może zawierać tylko cyfry')]
    public string $nip;

    #[Assert\NotBlank(message: 'Numer telefonu jest wymagany')]
    #[Assert\Regex(pattern: '/^\+?[0-9\s]+$/', message: 'Nieprawidłowy format numeru telefonu')]
    public string $phone;

    #[Assert\NotBlank(message: 'Adres email jest wymagany')]
    #[Assert\Email(message: 'Nieprawidłowy format adresu email')]
    public string $email;

    #[Assert\NotBlank(message: 'Imię właściciela jest wymagane')]
    public string $ownerFirstName;

    #[Assert\NotBlank(message: 'Nazwisko właściciela jest wymagane')]
    public string $ownerLastName;

    /**
     * @var array<string, string>|string The plain password
     */
    #[Assert\NotBlank(message: 'Hasło jest wymagane')]
    #[Assert\Length(
        min: 8,
        minMessage: 'Hasło musi mieć co najmniej {{ limit }} znaków',
        max: 4096,
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        message: 'Hasło musi zawierać przynajmniej jedną małą literę, jedną wielką literę i jedną cyfrę'
    )]
    public $plainPassword;

    #[Assert\IsTrue(message: 'Musisz zaakceptować regulamin')]
    public bool $agreeTerms;
}
