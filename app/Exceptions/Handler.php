<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $status = $response->status();

        return match ($status) {
            404 => Inertia::render('Errors/404')->toResponse($request)->setStatusCode($status),
            500, 503 => Inertia::render('Errors/500')->toResponse($request)->setStatusCode($status),
            403 => Inertia::render('Errors/403')->toResponse($request)->setStatusCode($status),
            401 => Inertia::render('Errors/401')->toResponse($request)->setStatusCode($status),
            419 => redirect()->back()->withErrors(['status' => __('The page expired, please try again.')]),
            default => $response
        };

    }

}
