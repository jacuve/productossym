<?php

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\ExceptionSubscriber;
use App\Service\ExceptionLogger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Symfony\Component\HttpKernel\KernelEvents;

#[AllowMockObjectsWithoutExpectations]
class ExceptionSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $events = ExceptionSubscriber::getSubscribedEvents();
        
        $this->assertArrayHasKey(KernelEvents::EXCEPTION, $events);
        $this->assertEquals('onKernelException', $events[KernelEvents::EXCEPTION][0]);
    }

    public function testConstructor(): void
    {
        $logger = $this->createMock(ExceptionLogger::class);
        
        $subscriber = new ExceptionSubscriber($logger);
        
        $this->assertInstanceOf(ExceptionSubscriber::class, $subscriber);
    }
}