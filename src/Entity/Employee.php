<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table(name: 'employees')]
class Employee implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_COMPANY_OWNER = 'ROLE_COMPANY_OWNER';
    public const ROLE_COMPANY_EMPLOYEE = 'ROLE_COMPANY_EMPLOYEE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Adres email jest wymagany')]
    #[Assert\Email(message: 'Nieprawidłowy format adresu email')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [self::ROLE_USER];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Imię jest wymagane')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Nazwisko jest wymagane')]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Regex(pattern: '/^\+?[0-9\s]+$/', message: 'Nieprawidłowy format numeru telefonu')]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Company::class)]
    private Collection $ownedCompanies;

    #[ORM\ManyToMany(targetEntity: Company::class, mappedBy: 'employees')]
    private Collection $employedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->ownedCompanies = new ArrayCollection();
        $this->employedAt = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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

    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getOwnedCompanies(): Collection
    {
        return $this->ownedCompanies;
    }

    public function addOwnedCompany(Company $company): static
    {
        if (!$this->ownedCompanies->contains($company)) {
            $this->ownedCompanies->add($company);
            $company->setOwner($this);

            // Make sure the user has the owner role
            $this->addRole(self::ROLE_COMPANY_OWNER);
        }

        return $this;
    }

    public function removeOwnedCompany(Company $company): static
    {
        if ($this->ownedCompanies->removeElement($company)) {
            // Set the owner to null (unless already changed)
            if ($company->getOwner() === $this) {
                $company->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getEmployedAt(): Collection
    {
        return $this->employedAt;
    }

    public function addEmployedAt(Company $employedAt): static
    {
        if (!$this->employedAt->contains($employedAt)) {
            $this->employedAt->add($employedAt);
            $employedAt->addEmployee($this);
        }

        return $this;
    }

    public function removeEmployedAt(Company $employedAt): static
    {
        if ($this->employedAt->removeElement($employedAt)) {
            $employedAt->removeEmployee($this);
        }

        return $this;
    }
}
