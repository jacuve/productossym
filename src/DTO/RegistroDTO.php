<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistroDTO
{
    #[Assert\NotBlank(message: 'El email es obligatorio')]
    #[Assert\Email(message: 'El email no es válido')]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'La contraseña es obligatoria')]
    #[Assert\Length(min: 6, minMessage: 'La contraseña debe tener al menos 6 caracteres')]
    public ?string $password = null;

    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'El nombre debe tener al menos 2 caracteres')]
    public ?string $nombre = null;

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->email = $data['email'] ?? null;
        $dto->password = $data['password'] ?? null;
        $dto->nombre = $data['nombre'] ?? null;
        return $dto;
    }
}
