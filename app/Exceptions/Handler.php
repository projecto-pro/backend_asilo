<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
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
            if (Config::get('database.default') == 'pgsql')
                $codigo = $exception->errorInfo[0];
            else
                $codigo = $exception->errorInfo[1];

            $message = "";

            switch ($codigo) {
                case 4060:
                    $message = "No se puede abrir la base de datos solicitada por el inicio de sesión";
                    break;
                case 40197:
                    $message = "Error en el servicio al procesar la solicitud. Vuelva a intentarlo";
                    break;
                case 40501:
                    $message = "El servicio está ocupado actualmente. Vuelva a intentar la solicitud después de 10 segundos";
                    break;
                case 40613:
                    $message = "Vuelva a intentar la conexión más tarde";
                    break;
                case 49918:
                    $message = "No se puede procesar la solicitud. No hay suficientes recursos para procesar la solicitud";
                    break;
                case 49919:
                    $message = "No se procesar, crear ni actualizar la solicitud. Hay demasiadas operaciones de creación o actualización en curso para la suscripción";
                    break;
                case 1451:
                    $message = "El registro se encuentra asociado, no podra realizar la eliminación";
                    break;
                case 104:
                    $message = "Debe haber elementos ORDER BY en la lista de selección si la instrucción contiene el operador UNION, INTERSECT o EXCEPT";
                    break;
                case 107:
                    $message = "El prefijo de columna no coincide con un nombre de tabla o un nombre de alias utilizado en la consulta";
                    break;
                case 109:
                    $message = "Hay más columnas en la instrucción INSERT que valores en la cláusula VALUES. El número de valores de VALUES debe coincidir con el de columnas de INSERT";
                    break;
                case 109:
                    $message = "Hay más columnas en la instrucción INSERT que valores en la cláusula VALUES. El número de valores de VALUES debe coincidir con el de columnas de INSERT";
                    break;
                case 1048:
                    $message = "Los campos no pueden ser nulos, debe de especificar un valor";
                    break;
                case 42703:
                    $message = "No existe una o varias columnas en la BD y que están referenciadas en el modelo";
                    break;
                case 1054:
                    $message = "No se encuentra la columna en la tabla";
                    break;
                case 1241:
                    $message = "El operando debe contener 1 columna (s)";
                    break;
                case 1604:
                    $message = "Error en el query";
                    break;
                default:
                    $message = "BD";
                    break;
            }

            return $this->errorResponse($exception->errorInfo, 422);
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
