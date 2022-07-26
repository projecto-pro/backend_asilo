<?php

namespace App\Traits;

use App\Models\Log;
use App\Models\Mes;
use App\Models\Persona;
use App\Models\RolMenu;
use App\Models\Municipio;
use App\Models\Protegido;
use App\Models\UsuarioRol;
use App\Models\FichaMedica;
use App\Models\Laboratorio;
use App\Models\Mensualidad;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Models\ConsultaMedica;
use App\Models\EvolucionMedica;
use App\Models\ClienteProveedor;
use Illuminate\Support\Facades\Auth;

trait ConsultasGlobal
{
    use Utileria;

    private $usuario;

    public function __construct()
    {
        $this->usuario = Auth::user();
    }

    protected function persona($request)
    {
        try {
            $persona = Persona::where('cui', $request->cui)->first();
            if (is_null($persona)) {
                $persona = new Persona();
                if (isset($request->foto)) {
                    $persona->foto = $this->saveImage($request->foto, 'persona', $request->cui);
                }
            } else {
                if (isset($request->foto)) {
                    $persona->foto = $this->saveImage($request->foto, 'persona', $request->cui, $persona->foto);
                }
            }

            $municipio = Municipio::find($request->municipio_id['id']);

            $persona->cui = $request->cui;
            $persona->primer_nombre = $request->primer_nombre;
            $persona->segundo_nombre = $request->segundo_nombre;
            $persona->primer_apellido = $request->primer_apellido;
            $persona->segundo_apellido = $request->segundo_apellido;
            $persona->email = $request->email;
            $persona->ubicacion = $request->ubicacion;
            $persona->telefono = $request->telefono;
            $persona->genero = $request->genero;
            $persona->departamento_id = $municipio->departamento_id;
            $persona->municipio_id = $municipio->id;
            $persona->save();

            /* SI LA PERSONA YA CUENTA CON FICHA MEDICA ACTUALIZAMOS SU INFORMACION */
            $protegido = Protegido::where('persona_id', $persona->id)->first();
            if (!is_null($protegido)) {
                $ficha_medica = FichaMedica::where('protegido_id', $protegido->id)->first();
                if (!is_null($ficha_medica)) {
                    $ficha_medica->cui = $persona->cui;
                    $ficha_medica->nombre_completo = str_replace("  ", " ", "{$persona->primer_nombre} {$persona->segundo_nombre} {$persona->primer_apellido} {$persona->segundo_apellido}");
                    $ficha_medica->genero = $persona->genero;
                    $ficha_medica->direccion = "{$municipio->getFullNameAttribute()}, {$persona->ubicacion}";
                    $ficha_medica->telefono = $persona->telefono;
                    $ficha_medica->save();
                }
            }

            return $persona;
        } catch (\Throwable $th) {
            $persona = Persona::where('cui', $request['cui'])->first();

            if (is_null($persona)) {
                $persona = new Persona();
                if (isset($request['foto'])) {
                    $persona->foto = $this->saveImage($request['foto'], 'persona', $request['cui']);
                }
            } else {
                if (isset($request['foto'])) {
                    $persona->foto = $this->saveImage($request['foto'], 'persona', $request['cui'], $persona->foto);
                }
            }

            $municipio = Municipio::find($request['municipio_id']['id']);

            $persona->cui = $request['cui'];
            $persona->primer_nombre = $request['primer_nombre'];
            $persona->segundo_nombre = $request['segundo_nombre'];
            $persona->primer_apellido = $request['primer_apellido'];
            $persona->segundo_apellido = $request['segundo_apellido'];
            $persona->email = $request['email'];
            $persona->ubicacion = $request['ubicacion'];
            $persona->telefono = $request['telefono'];
            $persona->genero = $request['genero'];
            $persona->departamento_id = $municipio->departamento_id;
            $persona->municipio_id = $municipio->id;
            $persona->save();

            /* SI LA PERSONA YA CUENTA CON FICHA MEDICA ACTUALIZAMOS SU INFORMACION */
            $protegido = Protegido::where('persona_id', $persona->id)->first();
            if (!is_null($protegido)) {
                $ficha_medica = FichaMedica::where('protegido_id', $protegido->id)->first();
                if (!is_null($ficha_medica)) {
                    $ficha_medica->cui = $persona->cui;
                    $ficha_medica->nombre_completo = str_replace("  ", " ", "{$persona->primer_nombre} {$persona->segundo_nombre} {$persona->primer_apellido} {$persona->segundo_apellido}");
                    $ficha_medica->genero = $persona->genero;
                    $ficha_medica->direccion = "{$municipio->getFullNameAttribute()}, {$persona->ubicacion}";
                    $ficha_medica->telefono = $persona->telefono;
                    $ficha_medica->save();
                }
            }

            return $persona;
        }
    }

    protected function asignar_roles(array $roles = [], $usuario_id)
    {
        UsuarioRol::where('usuario_id', $usuario_id)->forceDelete();
        foreach ($roles as $value) {
            $insert = new UsuarioRol();
            $insert->usuario_id = $usuario_id;
            $insert->rol_id = $value['id'];
            $insert->save();
        }
    }

    protected function asignar_menus(array $menus = [], $rol_id)
    {
        foreach ($menus as $value) {
            $menu_id = 0;

            if ($value['principal'] > 0) {
                $menu_id = $value['principal'];
            }
            if ($value['padre'] > 0) {
                $menu_id = $value['padre'];
            }
            if ($menu_id == 0) {
                $menu_id = $value['id'];
            }

            RolMenu::firstOrCreate(['rol_id' => $rol_id, 'menu_id' => $menu_id]);
            RolMenu::firstOrCreate(['rol_id' => $rol_id, 'menu_id' => $value['id']]);
        }
    }

    protected function cliente_proveedor(Request $request)
    {
        $persona = ClienteProveedor::where('nit', $request->nit)->first();
        if (is_null($persona)) {
            $persona = new ClienteProveedor();
        }

        $persona->nit = $request->nit;
        $persona->nombre = $request->nombre;
        $persona->telefonos = $request->telefonos;
        $persona->emails = $request->emails;
        $persona->direcciones = $request->direcciones;
        $persona->departamento_id = Municipio::find($request->municipio_id['id'])->departamento_id;
        $persona->municipio_id = $request->municipio_id['id'];
        $persona->usuario_id = $this->usuario->id;
        $persona->save();

        return $persona;
    }

    protected function protegido($paciente)
    {
        $paciente = $this->persona($paciente);

        $protegido = Protegido::where('persona_id', $paciente->id)->first();
        if (is_null($protegido)) {
            $protegido = new Protegido();
            $protegido->codigo = $this->generadorCodigo('P', count(Protegido::all()) + 1);
            $protegido->persona_id = $paciente->id;
            $protegido->usuario_id = $this->usuario->id;
        }

        $protegido->activo = true;
        $protegido->save();

        return $protegido;
    }

    protected function mesualidad(int $mes, int $ingreso_asilo_id, int $anio, bool $pagado = false, int $usuario_id = 0)
    {
        $mesualidad = Mensualidad::where('mes_id', $mes)->where('ingreso_asilo_id', $ingreso_asilo_id)->first();
        if (is_null($mesualidad)) {
            $mesualidad = new Mensualidad();
            $mesualidad->monto = 100;
            $mesualidad->pagado = $pagado;
            $mesualidad->fecha_pago = $pagado ? date('Y-m-d') : null;
            $mesualidad->anio = $anio;
            $mesualidad->ingreso_asilo_id = $ingreso_asilo_id;
            $mesualidad->mes_id = $mes;
            $mesualidad->usuario_id = is_null($this->usuario) ? $usuario_id : $this->usuario->id;
            $mesualidad->save();
        }
    }

    protected function agregarCobroFactura(int $evolucion_medica_id, float $subtotal, float $descuento)
    {
        $evolucion_medica = EvolucionMedica::find($evolucion_medica_id);
        $consulta_medica = ConsultaMedica::find($evolucion_medica->consulta_medica_id);
        $consulta_medica->subtotal += $subtotal;
        $consulta_medica->descuento += $descuento;
        $consulta_medica->total = $consulta_medica->subtotal - $consulta_medica->descuento;
        $consulta_medica->save();
    }

    protected function verificarPagoConsultaCompleta(int $evolucion_medica_id)
    {
        //PREGUNTAMOS SI AUN SE DEBE ALGUN MEDICAMENTO O LABORATORIO DE LA CONSULTA
        if (count(Tratamiento::where('evolucion_medica_id', $evolucion_medica_id)->get()) == 0 && count(Laboratorio::where('evolucion_medica_id', $evolucion_medica_id)->get()) == 0) {
            $evolucion_medica = EvolucionMedica::find($evolucion_medica_id);
            $consulta_medica = ConsultaMedica::find($evolucion_medica->consulta_medica_id);
            $consulta_medica->pagado = true;
            $consulta_medica->save();
        }
    }

    protected function grabarLog(string $excepcion, string $controlador)
    {
        $insert = new Log();
        $insert->descripcion = $excepcion;
        $insert->controlador = $controlador;
        $insert->usuario = is_null(
            $this->usuario
        ) ? 0 : $this->usuario->cui;
        $insert->usuarios_id = is_null(
            $this->usuario
        ) ? 0 : $this->usuario->id;
        $insert->save();
    }
}
