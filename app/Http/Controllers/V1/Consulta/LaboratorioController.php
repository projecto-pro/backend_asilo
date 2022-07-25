<?php

namespace App\Http\Controllers\V1\Consulta;

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
            return $this->showAll(Laboratorio::with('protegido.persona')->where('entregado', false)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
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
