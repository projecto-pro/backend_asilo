<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Models\Protegido;

class TratamientoController extends ApiController
{
    private $controlador_principal = 'TratamientoController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Tratamiento::with('protegido.persona', 'medicina_presentacion')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function edit(Protegido $tratamiento)
    {
        try {
            if (!$tratamiento->activo)
                return $this->errorResponse("El paciente con código {$tratamiento->codigo}, fue dado de bajo. Por tal razón no puede entregar el médicamento recetado.");

            $data = Tratamiento::with('protegido.persona', 'medicina_presentacion')->where('protegido_id', $tratamiento->id)->get();
            $sumatoria = Tratamiento::where('protegido_id', $tratamiento->id)->where('entregado', true)->sum('total');
            $articulos = Tratamiento::where('protegido_id', $tratamiento->id)->where('entregado', true)->count();

            return $this->successResponse(['data' => $data, 'sumatoria' => $sumatoria, 'articulos' => $articulos]);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function show(Tratamiento $tratamiento)
    {
        try {

            DB::beginTransaction();

            if (!$tratamiento->entregado) {
                $tratamiento->entregado = true;
                $tratamiento->save();

                $this->agregarCobroFactura($tratamiento->evolucion_medica_id, $tratamiento->subtotal, $tratamiento->descuento_aplicado);
            }

            DB::commit();

            return $this->successResponse('Registro entregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function destroy(Tratamiento $tratamiento)
    {
        try {
            DB::beginTransaction();

            $tratamiento->cancelado = true;
            $tratamiento->save();

            $this->verificarPagoConsultaCompleta($tratamiento->evolucion_medica_id);

            DB::commit();

            return $this->successResponse('Registro pagado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
