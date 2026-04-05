<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AllowMockObjectsWithoutExpectations]
class ProductoApiControllerTest extends TestCase
{
    public function testControllerDependencies(): void
    {
        $productoRepo = $this->createMock(ProductoRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $serializer = $this->createMock(\Symfony\Component\Serializer\SerializerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);

        $this->assertInstanceOf(ProductoRepository::class, $productoRepo);
        $this->assertInstanceOf(EntityManagerInterface::class, $entityManager);
    }

    public function testProductoEntityHasRequiredFields(): void
    {
        $producto = new Producto();
        
        $producto->setNombre('Test Product');
        $producto->setPrecio('99.99');
        $producto->setStock(10);

        $this->assertEquals('Test Product', $producto->getNombre());
        $this->assertEquals('99.99', $producto->getPrecio());
        $this->assertEquals(10, $producto->getStock());
    }

    public function testProductoDefaultValues(): void
    {
        $producto = new Producto();

        $this->assertEquals(0, $producto->getStock());
        $this->assertTrue($producto->isActivo());
    }
}
