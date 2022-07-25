<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\FichaMedica;
use App\Models\Laboratorio;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use App\Models\EvolucionMedica;
use App\Models\HistorialMedico;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class EvolucionMedicaController extends ApiController
{
    private $controlador_principal = 'EvolucionMedicaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function show(HistorialMedico $evolucion_medica)
    {
        try {
            return $this->showAll(EvolucionMedica::with('tratamientos', 'laboratorios.examen_realizado')->where('historial_medico_id', $evolucion_medica->id)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $historial_medico = HistorialMedico::find($request->historial_medico_id);

            $evolucion_medica = new EvolucionMedica();
            $evolucion_medica->correlativo = count(EvolucionMedica::where('historial_medico_id', $historial_medico->id)->get()) + 1;
            $evolucion_medica->fecha_hora = date('Y-m-d H:i:s');
            $evolucion_medica->encargado = $request->encargado;
            $evolucion_medica->evolucion = $request->evolucion;
            $evolucion_medica->historial_medico_id = $historial_medico->id;
            $evolucion_medica->ficha_medica_id = $historial_medico->ficha_medica_id;
            $evolucion_medica->consulta_medica_id = $historial_medico->consulta_medica_id;
            $evolucion_medica->medico_id = $historial_medico->medico_id;
            $evolucion_medica->save();

            //REGISTRAMOS LOS MEDICAMENTOS RECETADOS POR EL MEDICO
            foreach ($request->tratamientos as $tratamiento) {
                $recetar = new Tratamiento();
                $recetar->codigo_protegido = $evolucion_medica->ficha_medica->protegido->codigo;
                $recetar->cantidad = $tratamiento['cantidad'];
                $recetar->medicamento = $tratamiento['medicina_presentacion_id']['full_name'];
                $recetar->indicacion = $tratamiento['indicacion'];
                $recetar->precio = $tratamiento['medicina_presentacion_id']['precio'];
                $recetar->subtotal = $recetar->cantidad * $recetar->precio;
                $recetar->descuento = $recetar->precio - (($recetar->precio * $evolucion_medica->ficha_medica->solicitud_medica->ingreso_asilo->descuento) / 100);
                $recetar->descuento_aplicado = $recetar->cantidad * $recetar->descuento;
                $recetar->total = $recetar->subtotal - $recetar->descuento_aplicado;
                $recetar->cancelado = false;
                $recetar->entregado = false;
                $recetar->protegido_id = $evolucion_medica->ficha_medica->protegido->id;
                $recetar->evolucion_medica_id = $evolucion_medica->id;
                $recetar->medicina_presentacion_id = $tratamiento['medicina_presentacion_id']['id'];
                $recetar->usuario_id = Auth::user()->id;
                $recetar->save();
            }

            //REGISTRAMOS LOS LABORATORIOS SUGERIDOS POR EL MEDICO
            foreach ($request->laboratorios as $laboratorio) {
                $realizar = new Laboratorio();
                $realizar->codigo_protegido = $evolucion_medica->ficha_medica->protegido->codigo;
                $realizar->examen = $laboratorio['examen_id']['nombre'];
                $realizar->precio = $laboratorio['examen_id']['precio'];
                $realizar->subtotal = $realizar->precio;
                $realizar->descuento = $realizar->precio - (($realizar->precio * $evolucion_medica->ficha_medica->solicitud_medica->ingreso_asilo->descuento) / 100);
                $realizar->descuento_aplicado = $realizar->descuento;
                $realizar->total = $realizar->subtotal - $realizar->descuento_aplicado;
                $realizar->cancelado = false;
                $realizar->realizado = false;
                $realizar->protegido_id = $evolucion_medica->ficha_medica->protegido->id;
                $realizar->evolucion_medica_id = $evolucion_medica->id;
                $realizar->examen_id = $laboratorio['examen_id']['id'];
                $realizar->usuario_id = Auth::user()->id;
                $realizar->save();
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
