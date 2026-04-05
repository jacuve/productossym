<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioEntityTest extends TestCase
{
    public function testCreateUsuario(): void
    {
        $usuario = new Usuario();

        $usuario->setEmail('test@example.com');
        $usuario->setPassword('password123');
        $usuario->setNombre('Test User');
        $usuario->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('test@example.com', $usuario->getEmail());
        $this->assertEquals('password123', $usuario->getPassword());
        $this->assertEquals('Test User', $usuario->getNombre());
    }

    public function testGetUserIdentifier(): void
    {
        $usuario = new Usuario();
        $usuario->setEmail('test@example.com');

        $this->assertEquals('test@example.com', $usuario->getUserIdentifier());
    }

    public function testGetRoles(): void
    {
        $usuario = new Usuario();
        $usuario->setRoles(['ROLE_ADMIN']);

        $roles = $usuario->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    public function testGetRolesWithoutExtraRoles(): void
    {
        $usuario = new Usuario();
        $usuario->setRoles([]);

        $roles = $usuario->getRoles();

        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(1, $roles);
    }

    public function testEraseCredentials(): void
    {
        $usuario = new Usuario();

        $this->assertNull($usuario->eraseCredentials());
    }

    public function testFechaCreacionIsSetOnConstruct(): void
    {
        $usuario = new Usuario();

        $this->assertInstanceOf(\DateTimeImmutable::class, $usuario->getFechaCreacion());
    }

    public function testFechaActualizacionIsSetOnConstruct(): void
    {
        $usuario = new Usuario();

        $this->assertInstanceOf(\DateTimeImmutable::class, $usuario->getFechaActualizacion());
    }

    public function testActualizarFecha(): void
    {
        $usuario = new Usuario();
        $fechaOriginal = $usuario->getFechaActualizacion();

        sleep(1);
        $usuario->actualizarFecha();

        $this->assertGreaterThan($fechaOriginal, $usuario->getFechaActualizacion());
    }
}