<?php

namespace App\EventSubscriber;

use App\Service\ExceptionLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ExceptionLogger $exceptionLogger
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $statusCode = $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
            ? $exception->getStatusCode()
            : 500;

        $this->exceptionLogger->log($exception, $request, $statusCode);
    }
}
