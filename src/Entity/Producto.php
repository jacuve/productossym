<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
#[ORM\Table(name: 'productos')]
#[ORM\HasLifecycleCallbacks]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "El nombre es requerido")]
    #[Assert\Length(min: 2, max: 255, minMessage: "Mínimo 2 caracteres", maxMessage: "Máximo 255 caracteres")]
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[Assert\NotBlank(message: "El precio es requerido")]
    #[Assert\Positive(message: "El precio debe ser mayor que 0")]
    #[Assert\Type(type: "numeric", message: "El precio debe ser un número")]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $precio = null;

    #[Assert\NotBlank(message: "El stock es requerido")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "El stock no puede ser negativo")]
    #[Assert\Type(type: "integer", message: "El stock debe ser un entero")]
    #[ORM\Column]
    private ?int $stock = 0;

    #[Assert\GreaterThanOrEqual(value: 0, message: "El stock mínimo no puede ser negativo")]
    #[Assert\Type(type: "integer", message: "El stock mínimo debe ser un entero")]
    #[ORM\Column(nullable: true)]
    private ?int $stock_minimo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $codigo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categoria = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marca = null;

    #[Assert\GreaterThan(value: 0, message: "El peso debe ser mayor que 0")]
    #[ORM\Column(nullable: true)]
    private ?float $peso = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $unidad_medida = null;

    #[Assert\GreaterThanOrEqual(value: 0, message: "La cantidad mínima no puede ser negativa")]
    #[Assert\Type(type: "integer", message: "La cantidad mínima debe ser un entero")]
    #[ORM\Column(nullable: true)]
    private ?int $cantidad_minima = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_creacion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fecha_actualizacion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activo = true;

    public function __construct()
    {
        $this->fecha_creacion = new \DateTimeImmutable();
        $this->fecha_actualizacion = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): static
    {
        $this->precio = $precio;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getStockMinimo(): ?int
    {
        return $this->stock_minimo;
    }

    public function setStockMinimo(?int $stock_minimo): static
    {
        $this->stock_minimo = $stock_minimo;
        return $this;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): static
    {
        $this->codigo = $codigo;
        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(?string $categoria): static
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(?string $marca): static
    {
        $this->marca = $marca;
        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(?float $peso): static
    {
        $this->peso = $peso;
        return $this;
    }

    public function getUnidadMedida(): ?string
    {
        return $this->unidad_medida;
    }

    public function setUnidadMedida(?string $unidad_medida): static
    {
        $this->unidad_medida = $unidad_medida;
        return $this;
    }

    public function getCantidadMinima(): ?int
    {
        return $this->cantidad_minima;
    }

    public function setCantidadMinima(?int $cantidad_minima): static
    {
        $this->cantidad_minima = $cantidad_minima;
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

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): static
    {
        $this->activo = $activo;
        return $this;
    }

    #[ORM\PreUpdate]
    public function actualizarFecha(): void
    {
        $this->fecha_actualizacion = new \DateTimeImmutable();
    }

    public function isStockBajo(): bool
    {
        if ($this->stock_minimo === null) {
            return false;
        }
        return $this->stock <= $this->stock_minimo;
    }

    public function __toString(): string
    {
        return $this->nombre ?? 'Producto sin nombre';
    }
}
