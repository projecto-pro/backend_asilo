<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	private $fpdf;

	protected function successResponse($data, $code = 200)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code = 423)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return response()->json(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200, $action = 'SELECT')
	{
		return response()->json(['data' => $instance], $code);
	}
	protected function showMessage($message, $code = 210)
	{
		return $this->successResponse($message, $code);
	}

	protected function base_datos_message($e)
	{
		$message = "";

		switch ($e->errorInfo[1]) {
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
			default:
				$message = "BD";
				break;
		}

		return $message;
	}

	protected function traInformacion($data)
	{
		return (is_null($data) || !isset($data) || str_replace([" ", "null"], "", $data) === "" || $data == "false") ? false : true;
	}

	protected function formatoFecha($fecha)
	{
		return date('Y-m-d', strtotime($fecha));
	}

	protected function formatoMonto($monto)
	{
		return str_replace(",", "", $monto);
	}
}
