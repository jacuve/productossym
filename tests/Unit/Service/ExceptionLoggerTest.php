<?php

namespace App\Tests\Unit\Service;

use App\Service\ExceptionLogger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

#[AllowMockObjectsWithoutExpectations]
class ExceptionLoggerTest extends TestCase
{
    private ?LoggerInterface $logger = null;
    private ?ExceptionLogger $service = null;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = new ExceptionLogger($this->logger);
    }

    public function testLogWith500StatusCode(): void
    {
        $exception = new \Exception('Error message', 500);
        $request = $this->createMock(Request::class);
        
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/test');
        $request->method('getClientIp')->willReturn('127.0.0.1');
        $request->method('headers')->willReturnSelf();
        $request->headers->method('get')->with('User-Agent')->willReturn('Mozilla');

        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->with('Error message', $this->anything());

        $this->service->log($exception, $request, 500);
    }

    public function testLogWith400StatusCode(): void
    {
        $exception = new \Exception('Warning message', 400);
        $request = $this->createMock(Request::class);
        
        $request->method('getMethod')->willReturn('POST');
        $request->method('getUri')->willReturn('/api/test');
        $request->method('getClientIp')->willReturn('192.168.1.1');
        $request->method('headers')->willReturnSelf();
        $request->headers->method('get')->with('User-Agent')->willReturn('curl');

        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with('Warning message', $this->anything());

        $this->service->log($exception, $request, 400);
    }

    public function testLogWith200StatusCode(): void
    {
        $exception = new \Exception('Info message', 200);
        $request = $this->createMock(Request::class);
        
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/test');
        $request->method('getClientIp')->willReturn('127.0.0.1');
        $request->method('headers')->willReturnSelf();
        $request->headers->method('get')->with('User-Agent')->willReturn('TestAgent');

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with('Info message', $this->anything());

        $this->service->log($exception, $request, 200);
    }

    public function testLogHttpErrorWith500(): void
    {
        $request = $this->createMock(Request::class);
        
        $request->method('getMethod')->willReturn('GET');
        $request->method('getUri')->willReturn('/error');
        $request->method('getClientIp')->willReturn('127.0.0.1');

        $this->logger
            ->expects($this->once())
            ->method('critical')
            ->with('Server Error', $this->anything());

        $this->service->logHttpError(500, 'Server Error', $request);
    }

    public function testLogHttpErrorWith400(): void
    {
        $request = $this->createMock(Request::class);
        
        $request->method('getMethod')->willReturn('POST');
        $request->method('getUri')->willReturn('/bad-request');
        $request->method('getClientIp')->willReturn('10.0.0.1');

        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with('Bad Request', $this->anything());

        $this->service->logHttpError(404, 'Bad Request', $request);
    }
}