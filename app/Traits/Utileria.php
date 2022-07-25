<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait Utileria
{

    protected function generadorCodigo(string $palabra, int $correlativo, bool $incluir = false)
    {
        $correlativo = $correlativo === 0 ? 1 : $correlativo + 1;
        $codigo = str_pad(strval($correlativo), 5, "0", STR_PAD_LEFT);
        $anio = date('Y');
        return $incluir ? "{$palabra}{$codigo}{$anio}" : "{$palabra}{$codigo}";
    }

    protected function getB64Image($base64_image)
    {
        $image_service_str = substr($base64_image, strpos($base64_image, ",") + 1);
        $image = base64_decode($image_service_str);
        return $image;
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

    protected function saveImage(string $imagen, string $storage, string $nombre, string $eliminar = null)
    {
        if (!is_null($eliminar))
            Storage::disk($storage)->exists($eliminar) ? Storage::disk($storage)->delete($eliminar) : null;

        $path = null;
        if (!is_null($imagen)) {
            $img = $this->getB64Image($imagen);
            $image = Image::make($img);
            $image->encode('jpg', 70);
            $path = "{$nombre}.jpg";
            Storage::disk($storage)->put("{$nombre}.jpg", $image);
        }

        return $path;
    }
}
