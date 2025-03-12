<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\Table(name: 'companies')]
#[UniqueEntity(fields: ['nip'], message: 'Ten NIP jest już zarejestrowany w systemie')]
#[UniqueEntity(fields: ['email'], message: 'Ten adres email jest już zarejestrowany w systemie')]
class Company
{
    public const STATUS_PENDING = 'oczekująca na weryfikację';
    public const STATUS_ACTIVE = 'aktywna';
    public const STATUS_INACTIVE = 'nieaktywna';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Nazwa firmy jest wymagana')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Adres siedziby jest wymagany')]
    private ?string $address = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Assert\NotBlank(message: 'NIP jest wymagany')]
    #[Assert\Length(exactly: 10, exactMessage: 'NIP musi składać się z dokładnie 10 cyfr')]
    #[Assert\Regex(pattern: '/^\d{10}$/', message: 'NIP może zawierać tylko cyfry')]
    private ?string $nip = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'Numer telefonu jest wymagany')]
    #[Assert\Regex(pattern: '/^\+?[0-9\s]+$/', message: 'Nieprawidłowy format numeru telefonu')]
    private ?string $phone = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Adres email jest wymagany')]
    #[Assert\Email(message: 'Nieprawidłowy format adresu email')]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $status = self::STATUS_PENDING;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    #[ORM\ManyToOne(inversedBy: 'ownedCompanies')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Employee $owner = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'employedAt')]
    #[ORM\JoinTable(name: 'company_employees')]
    private Collection $employees;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(string $nip): static
    {
        $this->nip = $nip;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function isVerified(): bool
    {
        return self::STATUS_ACTIVE === $this->status;
    }

    public function getOwner(): ?Employee
    {
        return $this->owner;
    }

    public function setOwner(?Employee $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            // Make sure the employee has the correct role
            $employee->addRole(Employee::ROLE_COMPANY_EMPLOYEE);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        $this->employees->removeElement($employee);

        return $this;
    }
}
