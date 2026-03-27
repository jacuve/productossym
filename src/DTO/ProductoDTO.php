<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductoDTO
{
    #[Assert\NotBlank(message: 'El nombre es obligatorio')]
    #[Assert\Length(max: 255, maxMessage: 'El nombre no puede superar los 255 caracteres')]
    public ?string $nombre = null;

    #[Assert\Type('string')]
    public ?string $descripcion = null;

    #[Assert\NotBlank(message: 'El precio es obligatorio')]
    #[Assert\Positive(message: 'El precio debe ser un número positivo')]
    public ?float $precio = null;

    #[Assert\PositiveOrZero(message: 'El stock debe ser cero o positivo')]
    public int $stock = 0;

    #[Assert\Positive(message: 'El stock mínimo debe ser positivo')]
    public ?int $stockMinimo = null;

    #[Assert\Length(max: 100, maxMessage: 'El código no puede superar los 100 caracteres')]
    public ?string $codigo = null;

    #[Assert\Length(max: 255)]
    public ?string $categoria = null;

    #[Assert\Length(max: 255)]
    public ?string $marca = null;

    #[Assert\Positive(message: 'El peso debe ser positivo')]
    public ?float $peso = null;

    #[Assert\Length(max: 100)]
    public ?string $unidadMedida = null;

    #[Assert\Positive(message: 'La cantidad mínima debe ser positiva')]
    public ?int $cantidadMinima = null;

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->nombre = $data['nombre'] ?? null;
        $dto->descripcion = $data['descripcion'] ?? null;
        $dto->precio = isset($data['precio']) ? (float) $data['precio'] : null;
        $dto->stock = isset($data['stock']) ? (int) $data['stock'] : 0;
        $dto->stockMinimo = isset($data['stockMinimo']) ? (int) $data['stockMinimo'] : null;
        $dto->codigo = $data['codigo'] ?? null;
        $dto->categoria = $data['categoria'] ?? null;
        $dto->marca = $data['marca'] ?? null;
        $dto->peso = isset($data['peso']) ? (float) $data['peso'] : null;
        $dto->unidadMedida = $data['unidadMedida'] ?? null;
        $dto->cantidadMinima = isset($data['cantidadMinima']) ? (int) $data['cantidadMinima'] : null;
        return $dto;
    }
}
