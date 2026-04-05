<?php

namespace App\Tests\Unit\DTO;

use App\DTO\ProductoDTO;
use PHPUnit\Framework\TestCase;

class ProductoDTOTest extends TestCase
{
    public function testFromArrayConDatosCompletos(): void
    {
        $data = [
            'nombre' => 'Producto Test',
            'descripcion' => 'Descripción test',
            'precio' => '99.99',
            'stock' => '10',
            'stockMinimo' => '5',
            'codigo' => 'PROD-001',
            'categoria' => 'Electronics',
            'marca' => 'TestBrand',
            'peso' => '1.5',
            'unidadMedida' => 'kg',
            'cantidadMinima' => '2',
        ];

        $dto = ProductoDTO::fromArray($data);

        $this->assertEquals('Producto Test', $dto->nombre);
        $this->assertEquals('Descripción test', $dto->descripcion);
        $this->assertEquals(99.99, $dto->precio);
        $this->assertEquals(10, $dto->stock);
        $this->assertEquals(5, $dto->stockMinimo);
        $this->assertEquals('PROD-001', $dto->codigo);
        $this->assertEquals('Electronics', $dto->categoria);
        $this->assertEquals('TestBrand', $dto->marca);
        $this->assertEquals(1.5, $dto->peso);
        $this->assertEquals('kg', $dto->unidadMedida);
        $this->assertEquals(2, $dto->cantidadMinima);
    }

    public function testFromArrayConDatosMinimos(): void
    {
        $data = [
            'nombre' => 'Producto Minimo',
            'precio' => '50.00',
        ];

        $dto = ProductoDTO::fromArray($data);

        $this->assertEquals('Producto Minimo', $dto->nombre);
        $this->assertEquals(50.00, $dto->precio);
        $this->assertEquals(0, $dto->stock);
        $this->assertNull($dto->descripcion);
        $this->assertNull($dto->stockMinimo);
        $this->assertNull($dto->codigo);
    }

    public function testFromArrayConDatosVacios(): void
    {
        $dto = ProductoDTO::fromArray([]);

        $this->assertNull($dto->nombre);
        $this->assertNull($dto->precio);
        $this->assertEquals(0, $dto->stock);
        $this->assertNull($dto->descripcion);
        $this->assertNull($dto->stockMinimo);
    }

    public function testFromArrayConPrecioString(): void
    {
        $data = [
            'nombre' => 'Producto',
            'precio' => '100.50',
        ];

        $dto = ProductoDTO::fromArray($data);

        $this->assertEquals(100.50, $dto->precio);
        $this->assertIsFloat($dto->precio);
    }

    public function testFromArrayConStockString(): void
    {
        $data = [
            'nombre' => 'Producto',
            'precio' => '100',
            'stock' => '25',
        ];

        $dto = ProductoDTO::fromArray($data);

        $this->assertEquals(25, $dto->stock);
        $this->assertIsInt($dto->stock);
    }
}