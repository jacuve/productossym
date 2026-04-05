<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Producto;
use PHPUnit\Framework\TestCase;

class ProductoEntityTest extends TestCase
{
    public function testCreateProducto(): void
    {
        $producto = new Producto();
        
        $producto->setNombre('Test Product');
        $producto->setDescripcion('Test Description');
        $producto->setPrecio('99.99');
        $producto->setStock(10);
        $producto->setStockMinimo(5);
        $producto->setCodigo('PROD001');
        $producto->setCategoria('Electronics');
        $producto->setMarca('TestBrand');
        $producto->setPeso(1.5);
        $producto->setUnidadMedida('kg');

        $this->assertEquals('Test Product', $producto->getNombre());
        $this->assertEquals('Test Description', $producto->getDescripcion());
        $this->assertEquals('99.99', $producto->getPrecio());
        $this->assertEquals(10, $producto->getStock());
        $this->assertEquals(5, $producto->getStockMinimo());
        $this->assertEquals('PROD001', $producto->getCodigo());
        $this->assertEquals('Electronics', $producto->getCategoria());
        $this->assertEquals('TestBrand', $producto->getMarca());
        $this->assertEquals(1.5, $producto->getPeso());
        $this->assertEquals('kg', $producto->getUnidadMedida());
    }

    public function testDefaultValues(): void
    {
        $producto = new Producto();

        $this->assertEquals(0, $producto->getStock());
        $this->assertTrue($producto->isActivo());
        $this->assertNull($producto->getDescripcion());
        $this->assertNull($producto->getStockMinimo());
    }

    public function testIsStockBajo(): void
    {
        $producto = new Producto();
        $producto->setNombre('Test');
        $producto->setPrecio('100');
        $producto->setStock(5);
        $producto->setStockMinimo(10);

        $this->assertTrue($producto->isStockBajo());
    }

    public function testIsNotStockBajo(): void
    {
        $producto = new Producto();
        $producto->setNombre('Test');
        $producto->setPrecio('100');
        $producto->setStock(15);
        $producto->setStockMinimo(10);

        $this->assertFalse($producto->isStockBajo());
    }

    public function testIsStockBajoWithNullMinimo(): void
    {
        $producto = new Producto();
        $producto->setNombre('Test');
        $producto->setPrecio('100');
        $producto->setStock(5);
        $producto->setStockMinimo(null);

        $this->assertFalse($producto->isStockBajo());
    }

    public function testToString(): void
    {
        $producto = new Producto();
        $producto->setNombre('Test Product');

        $this->assertEquals('Test Product', (string) $producto);
    }

    public function testToStringWithNull(): void
    {
        $producto = new Producto();

        $this->assertEquals('Producto sin nombre', (string) $producto);
    }
}
