<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\FichaMedica;
use App\Models\Laboratorio;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Models\ConsultaMedica;
use App\Models\EvolucionMedica;
use App\Models\HistorialMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class HistorialMedicoController extends ApiController
{
    private $controlador_principal = 'HistorialMedicoController';

    public function __construct()
    {
        parent::__construct();
    }

    public function show(FichaMedica $historial_medico)
    {
        try {
            return $this->showAll(HistorialMedico::with('consulta_medica.solicitud_medica', 'medico', 'evoluciones.tratamientos', 'evoluciones.laboratorios.examen_realizado')->where('ficha_medica_id', $historial_medico->id)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $consulta_medica = ConsultaMedica::find($request->consulta_medica_id);
            $ficha_historica = FichaMedica::find($request->ficha_medica_id);

            $historial_medico = new HistorialMedico();
            $historial_medico->correlativo = HistorialMedico::where('ficha_medica_id', $ficha_historica->id)->count() + 1;
            $historial_medico->fecha_hora_inicio = date('Y-m-d H:i:s');
            $historial_medico->fecha_hora_finaliza = null;
            $historial_medico->encargado = $request->encargado;
            $historial_medico->diagnostico = $request->diagnostico;
            $historial_medico->concluida = false;
            $historial_medico->ficha_medica_id = $ficha_historica->id;
            $historial_medico->consulta_medica_id = $consulta_medica->id;
            $historial_medico->medico_id = $consulta_medica->medico_id;
            $historial_medico->save();

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function destroy(HistorialMedico $historial_medico)
    {
        try {
            $historial_medico->fecha_hora_finaliza = date('Y-m-d H:i:s');
            $historial_medico->concluida = $historial_medico->concluida ? false : true;
            $historial_medico->save();

            return $this->successResponse('Registro cambio de estado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
