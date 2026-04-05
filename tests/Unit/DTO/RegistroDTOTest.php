<?php

namespace App\Tests\Unit\DTO;

use App\DTO\RegistroDTO;
use PHPUnit\Framework\TestCase;

class RegistroDTOTest extends TestCase
{
    public function testFromArrayConDatosCompletos(): void
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'nombre' => 'Test User',
        ];

        $dto = RegistroDTO::fromArray($data);

        $this->assertEquals('test@example.com', $dto->email);
        $this->assertEquals('password123', $dto->password);
        $this->assertEquals('Test User', $dto->nombre);
    }

    public function testFromArrayConDatosMinimos(): void
    {
        $data = [
            'email' => 'user@test.com',
            'password' => '123456',
            'nombre' => 'Juan',
        ];

        $dto = RegistroDTO::fromArray($data);

        $this->assertEquals('user@test.com', $dto->email);
        $this->assertEquals('123456', $dto->password);
        $this->assertEquals('Juan', $dto->nombre);
    }

    public function testFromArrayConDatosVacios(): void
    {
        $dto = RegistroDTO::fromArray([]);

        $this->assertNull($dto->email);
        $this->assertNull($dto->password);
        $this->assertNull($dto->nombre);
    }

    public function testFromArrayConAlgunosDatos(): void
    {
        $data = [
            'email' => 'partial@test.com',
            'password' => 'pass',
        ];

        $dto = RegistroDTO::fromArray($data);

        $this->assertEquals('partial@test.com', $dto->email);
        $this->assertEquals('pass', $dto->password);
        $this->assertNull($dto->nombre);
    }
}