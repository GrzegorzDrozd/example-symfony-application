<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'app_error')]
    public function index(Throwable $exception): JsonResponse
    {
        return match (true) {
            $exception instanceof NotFoundHttpException => new JsonResponse(['error' => 'Resource not found.']),
            default => new JsonResponse(['error' => $exception->getMessage(), 'type' => get_class($exception)]),
        };
    }
}
