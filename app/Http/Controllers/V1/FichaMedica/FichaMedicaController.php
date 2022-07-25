<?php

namespace App\Http\Controllers\V1\FichaMedica;

use App\Models\Municipio;
use App\Models\Protegido;
use App\Models\FichaMedica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\FichaMedicaAntecedente;
use App\Http\Controllers\ApiController;

class FichaMedicaController extends ApiController
{
    private $controlador_principal = 'FichaMedicaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function show(Protegido $ficha_medica)
    {
        try {
            $data = FichaMedica::with('protegido.persona', 'contacto.municipio', 'medico_tratante', 'antecedentes.antecedente')->where('protegido_id', $ficha_medica->id)->get();

            return $this->successResponse(['data' => count($data) == 0 ? null : $data[0]]);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $contacto = $this->persona($request->contacto);
            $protegido = Protegido::find($request->protegido_id);
            $municipio = Municipio::find($protegido->persona->municipio_id);

            if (!is_null(FichaMedica::where('protegido_id', $protegido->id)->first())) {
                return $this->errorResponse("El paciente ya cuenta con una ficha médica registrada en el sistema.");
            }

            $ficha_medica = new FichaMedica();
            $ficha_medica->expediente = $this->generadorCodigo("EXP", FichaMedica::count() + 1, true);
            $ficha_medica->fecha_ingreso = date('Y-m-d');
            $ficha_medica->hora_ingreso = date('H:i:s');
            $ficha_medica->fecha_nacimiento = date('Y-m-d', strtotime($request->fecha_nacimiento));
            $ficha_medica->cui = $protegido->persona->cui;
            $ficha_medica->nombre_completo = str_replace("  ", " ", "{$protegido->persona->primer_nombre} {$protegido->persona->segundo_nombre} {$protegido->persona->primer_apellido} {$protegido->persona->segundo_apellido}");
            $ficha_medica->genero = $protegido->persona->genero;
            $ficha_medica->direccion = "{$municipio->getFullNameAttribute()}, {$protegido->persona->ubicacion}";
            $ficha_medica->telefono = $protegido->persona->telefono;
            $ficha_medica->alergia = $request->alergia;
            $ficha_medica->protegido_id = $protegido->id;
            $ficha_medica->ingreso_asilo_id = $request->ingreso_asilo_id;
            $ficha_medica->contacto_id = $contacto->id;
            $ficha_medica->solicitud_medica_id = $request->solicitud_medica_id;
            $ficha_medica->medico_tratante_id = $request->medico_tratante_id;
            $ficha_medica->usuario_id = Auth::user()->id;
            $ficha_medica->save();

            foreach ($request->antecedentes as $antecedente) {
                $ficha_medica_antecedente = new FichaMedicaAntecedente();
                $ficha_medica_antecedente->ficha_medica_id = $ficha_medica->id;
                $ficha_medica_antecedente->antecedente_id = $antecedente['id'];
                $ficha_medica_antecedente->save();
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }
}
