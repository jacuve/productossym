<?php

namespace App\Tests\Unit\Service;

use App\Service\ExceptionLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ExceptionLoggerTest extends TestCase
{
    private ?LoggerInterface $logger = null;
    private ?ExceptionLogger $service = null;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = new ExceptionLogger($this->logger);
    }

    public function testLogWith500StatusCodeCallsCritical(): void
    {
        $exception = new \Exception('Error message', 500);
        $request = new \Symfony\Component\HttpFoundation\Request();
        
        $this->logger
            ->expects($this->once())
            ->method('critical');

        $this->service->log($exception, $request, 500);
    }

    public function testLogWith400StatusCodeCallsWarning(): void
    {
        $exception = new \Exception('Warning message', 400);
        $request = new \Symfony\Component\HttpFoundation\Request();
        
        $this->logger
            ->expects($this->once())
            ->method('warning');

        $this->service->log($exception, $request, 400);
    }

    public function testLogWith200StatusCodeCallsError(): void
    {
        $exception = new \Exception('Info message', 200);
        $request = new \Symfony\Component\HttpFoundation\Request();
        
        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->service->log($exception, $request, 200);
    }

    public function testLogHttpErrorWith500CallsCritical(): void
    {
        $request = new \Symfony\Component\HttpFoundation\Request();
        
        $this->logger
            ->expects($this->once())
            ->method('critical');

        $this->service->logHttpError(500, 'Server Error', $request);
    }

    public function testLogHttpErrorWith400CallsWarning(): void
    {
        $request = new \Symfony\Component\HttpFoundation\Request();
        
        $this->logger
            ->expects($this->once())
            ->method('warning');

        $this->service->logHttpError(404, 'Not Found', $request);
    }
}