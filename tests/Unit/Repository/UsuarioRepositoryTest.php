<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

#[AllowMockObjectsWithoutExpectations]
class UsuarioRepositoryTest extends TestCase
{
    private ?ManagerRegistry $registry = null;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
    }

    public function testRepositoryExists(): void
    {
        $repository = new UsuarioRepository($this->registry);
        $this->assertInstanceOf(UsuarioRepository::class, $repository);
    }

    public function testUpgradePasswordThrowsExceptionForInvalidUser(): void
    {
        $this->expectException(\TypeError::class);
        
        $repository = new UsuarioRepository($this->registry);
        $invalidUser = new \stdClass();
        
        $repository->upgradePassword($invalidUser, 'newhash');
    }

    public function testUpgradePasswordThrowsExceptionForNonUsuario(): void
    {
        $this->expectException(UnsupportedUserException::class);
        
        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        
        $this->registry
            ->method('getManager')
            ->willReturn($entityManager);

        $otherUser = $this->createMock(\Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface::class);
        
        $repository = new UsuarioRepository($this->registry);
        $repository->upgradePassword($otherUser, 'newhash');
    }
}