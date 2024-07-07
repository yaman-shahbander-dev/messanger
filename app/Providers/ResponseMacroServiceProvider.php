<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Enums\HttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMacros();
    }

    protected function registerMacros(): void
    {
        $this->registerOkResponseMacro();
        $this->registerFailedResponseMacro();
        $this->registerUnprocessableResponseMacro();
        $this->registerNotFoundResponseMacro();
        $this->registerUnauthorizedResponseMacro();
        $this->registerForbiddenResponseMacro();
        $this->registerServerErrorResponseMacro();
    }

    protected function registerOkResponseMacro(): void
    {
        Response::macro('ok', function ($data = null, $message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::OK->value), 'data' => $data], HttpStatus::OK->value, $headers);
        });
    }

    protected function registerFailedResponseMacro(): void
    {
        Response::macro('failed', function ($message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::BAD_REQUEST->value)], HttpStatus::BAD_REQUEST->value, $headers);
        });
    }

    protected function registerUnprocessableResponseMacro(): void
    {
        Response::macro('unprocessable', function ($errors = null, $message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::UNPROCESSABLE_ENTITY->value), 'errors' => $errors], HttpStatus::UNPROCESSABLE_ENTITY->value, $headers);
        });
    }

    protected function registerNotFoundResponseMacro(): void
    {
        Response::macro('notFound', function ($message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::NOT_FOUND->value)], HttpStatus::NOT_FOUND->value, $headers);
        });
    }

    protected function registerUnauthorizedResponseMacro(): void
    {
        Response::macro('unauthorized', function ($message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::UNAUTHORIZED->value)], HttpStatus::UNAUTHORIZED->value, $headers);
        });
    }

    protected function registerForbiddenResponseMacro(): void
    {
        Response::macro('forbidden', function ($message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::HTTP_FORBIDDEN->value)], HttpStatus::HTTP_FORBIDDEN->value, $headers);
        });
    }

    protected function registerServerErrorResponseMacro(): void
    {
        Response::macro('serverError', function ($message = null, $headers = []) {
            return new JsonResponse(['message' => $message ?? HttpStatus::msg(HttpStatus::INTERNAL_SERVER_ERROR->value)], HttpStatus::INTERNAL_SERVER_ERROR->value, $headers);
        });
    }
}
