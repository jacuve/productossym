<?php

namespace App\Tests\Unit\Security;

use App\Security\JsonLoginAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

#[AllowMockObjectsWithoutExpectations]
class JsonLoginAuthenticatorTest extends TestCase
{
    private ?JWTTokenManagerInterface $jwtManager = null;
    private ?JsonLoginAuthenticator $authenticator = null;

    protected function setUp(): void
    {
        $this->jwtManager = $this->createMock(JWTTokenManagerInterface::class);
        $this->authenticator = new JsonLoginAuthenticator($this->jwtManager);
    }

    public function testSupportsConLoginApiPOST(): void
    {
        $request = Request::create('/api/login', 'POST');
        
        $result = $this->authenticator->supports($request);
        
        $this->assertTrue($result);
    }

    public function testSupportsConPathDiferente(): void
    {
        $request = Request::create('/api/other', 'POST');
        
        $result = $this->authenticator->supports($request);
        
        $this->assertFalse($result);
    }

    public function testSupportsConMetodoGet(): void
    {
        $request = Request::create('/api/login', 'GET');
        
        $result = $this->authenticator->supports($request);
        
        $this->assertFalse($result);
    }

    public function testSupportsConPathConBarra(): void
    {
        $request = Request::create('/api/login/', 'POST');
        
        $result = $this->authenticator->supports($request);
        
        $this->assertFalse($result);
    }

    public function testAuthenticateConDatosValidos(): void
    {
        $request = Request::create('/api/login', 'POST', [], [], [], [], json_encode([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]));

        $passport = $this->authenticator->authenticate($request);
        
        $this->assertNotNull($passport);
    }

    public function testAuthenticateSinEmail(): void
    {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        
        $request = Request::create('/api/login', 'POST', [], [], [], [], json_encode([
            'password' => 'password123',
        ]));

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateSinPassword(): void
    {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        
        $request = Request::create('/api/login', 'POST', [], [], [], [], json_encode([
            'email' => 'test@example.com',
        ]));

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateConEmailVacio(): void
    {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        
        $request = Request::create('/api/login', 'POST', [], [], [], [], json_encode([
            'email' => '',
            'password' => 'password123',
        ]));

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateConJsonInvalido(): void
    {
        $this->expectException(CustomUserMessageAuthenticationException::class);
        
        $request = Request::create('/api/login', 'POST', [], [], [], [], 'invalid json');

        $this->authenticator->authenticate($request);
    }

    public function testOnAuthenticationFailure(): void
    {
        $request = Request::create('/api/login', 'POST');
        $exception = new CustomUserMessageAuthenticationException('Credenciales inválidas');

        $response = $this->authenticator->onAuthenticationFailure($request, $exception);
        
        $this->assertEquals(401, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Credenciales inválidas', $content['error']);
    }
}