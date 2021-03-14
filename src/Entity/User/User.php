<?php

namespace App\Entity\User;

use App\Repository\User\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $confirmedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $passwordResetToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $passwordRequestedAt;

    public function __construct(
        string $email
    ) {
        $this->email = $email;
        $this->roles = [];
        $this->confirmationToken = $this->generateToken();
        $this->enabled = false;
        $this->passwordResetToken = null;
        $this->passwordRequestedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): string
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;

        if ($this->passwordResetToken !== null) {
            $this->passwordResetToken = null;
        }

        if ($this->passwordRequestedAt !== null) {
            $this->passwordRequestedAt = null;
        }
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function getConfirmedAt(): ?DateTime
    {
        return $this->confirmedAt;
    }

    public function isConfirmed(): bool
    {
        return null !== $this->confirmedAt;
    }

    public function confirm(): void
    {
        $this->confirmationToken = null;
        $this->confirmedAt = new DateTime();
        $this->enabled = true;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function getPasswordRequestedAt(): ?DateTime
    {
        return $this->passwordRequestedAt;
    }

    public function requestPassword(): void
    {
        $this->passwordResetToken = $this->generateToken();
        $this->passwordRequestedAt = new DateTime();
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
