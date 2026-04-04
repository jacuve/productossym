<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class ExceptionLogger
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function log(Throwable $exception, Request $request, ?int $statusCode = null): void
    {
        $context = [
            'exception_message' => $exception->getMessage(),
            'exception_code' => $exception->getCode(),
            'status_code' => $statusCode,
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ];

        if ($statusCode && $statusCode >= 500) {
            $this->logger->critical($exception->getMessage(), $context);
        } elseif ($statusCode && $statusCode >= 400) {
            $this->logger->warning($exception->getMessage(), $context);
        } else {
            $this->logger->error($exception->getMessage(), $context);
        }
    }

    public function logHttpError(int $statusCode, string $message, Request $request): void
    {
        $context = [
            'status_code' => $statusCode,
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'ip' => $request->getClientIp(),
        ];

        if ($statusCode >= 500) {
            $this->logger->critical($message, $context);
        } else {
            $this->logger->warning($message, $context);
        }
    }
}
