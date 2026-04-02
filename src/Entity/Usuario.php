<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'usuarios')]
#[ORM\HasLifecycleCallbacks]
class Usuario implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'El email es obligatorio')]
    #[Assert\Email(message: 'El email no es válido')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'El nombre debe tener al menos 2 caracteres')]
    private ?string $nombre = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_creacion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_actualizacion = null;

    public function __construct()
    {
        $this->fecha_creacion = new \DateTimeImmutable();
        $this->fecha_actualizacion = new \DateTimeImmutable();
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeImmutable
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(?\DateTimeImmutable $fecha_creacion): static
    {
        $this->fecha_creacion = $fecha_creacion;
        return $this;
    }

    public function getFechaActualizacion(): ?\DateTimeImmutable
    {
        return $this->fecha_actualizacion;
    }

    public function setFechaActualizacion(?\DateTimeImmutable $fecha_actualizacion): static
    {
        $this->fecha_actualizacion = $fecha_actualizacion;
        return $this;
    }

    #[ORM\PreUpdate]
    public function actualizarFecha(): void
    {
        $this->fecha_actualizacion = new \DateTimeImmutable();
    }
}
