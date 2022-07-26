<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Laravel\Passport\Exceptions\MissingScopeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function render($request, Throwable $exception)
    {
        if ($exception->getCode() === 401) {
            return $this->errorResponse("Usuario o contraseña incorrectos", 401);
        }

        if ($exception instanceof RouteNotFoundException) {
            return $this->errorResponse("La ruta no fue encontrada", 422);
        }

        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el ID especificado", 422);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof MissingScopeException) {

            return $this->errorResponse("No posee permisos para ejectura esta acción", 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("No se encontró la URL especificada", 422);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El método especificado en la petición no es válido", 422);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            return $this->errorResponse($exception->getMessage(), 422);
        }

        if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if (!config('app.debug')) {
            return $this->errorResponse("Falla inesperada, intente luego", 500);
        }

        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $exception)
            : $this->prepareResponse($request, $exception);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json($exception->errors(), 409);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('No autenticado', 401);
    }

    protected function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
