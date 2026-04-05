<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
class ProductoRepositoryTest extends TestCase
{
    private ?ManagerRegistry $registry = null;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
    }

    public function testRepositoryExists(): void
    {
        $repository = new ProductoRepository($this->registry);
        $this->assertInstanceOf(ProductoRepository::class, $repository);
    }
}
