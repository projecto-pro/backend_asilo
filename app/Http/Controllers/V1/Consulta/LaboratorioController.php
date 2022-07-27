<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\Protegido;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class LaboratorioController extends ApiController
{
    private $controlador_principal = 'LaboratorioController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Laboratorio::with('protegido.persona')->where('realizado', false)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function edit(Protegido $laboratorio)
    {
        try {
            if (!$laboratorio->activo)
                return $this->errorResponse("El paciente con código {$laboratorio->codigo}, fue dado de bajo. Por tal razón no puede realizar el examen.");

            $data = Laboratorio::with('protegido.persona', 'evolucion_medica')->where('realizado', false)->where('protegido_id', $laboratorio->id)->get();
            $sumatoria = Laboratorio::where('protegido_id', $laboratorio->id)->where('realizado', true)->sum('total');
            $articulos = Laboratorio::where('protegido_id', $laboratorio->id)->where('realizado', true)->count();

            return $this->successResponse(['data' => $data, 'sumatoria' => $sumatoria, 'articulos' => $articulos]);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@edit");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function show(Laboratorio $laboratorio)
    {
        try {

            DB::beginTransaction();

            if (!$laboratorio->realizado) {
                $laboratorio->realizado = true;
                $laboratorio->save();

                $this->agregarCobroFactura($laboratorio->evolucion_medica_id, $laboratorio->subtotal, $laboratorio->descuento_aplicado);
            }

            DB::commit();

            return $this->successResponse('Registro realizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function destroy(Laboratorio $laboratorio)
    {
        try {
            DB::beginTransaction();

            $laboratorio->cancelado = true;
            $laboratorio->save();

            $this->verificarPagoConsultaCompleta($laboratorio->evolucion_medica_id);

            DB::commit();

            return $this->successResponse('Registro pagado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
