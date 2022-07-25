<?php

namespace App\Http\Controllers\V1\FichaMedica;

use App\Models\Antecedente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FichaMedicaAntecedente;
use App\Http\Controllers\ApiController;

class FichaMedicaAntecedenteController extends ApiController
{
    private $controlador_principal = 'FichaMedicaAntecedenteController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $antecedente = Antecedente::where('nombre', $request->antecedente)->first();
            if (is_null($antecedente)) {
                $antecedente = new Antecedente();
                $antecedente->nombre = $request->antecedente;
                $antecedente->save();
            }

            if (!is_null(FichaMedicaAntecedente::where('ficha_medica_id', $request->id)->where('antecedente_id', $antecedente->id)->first())) {
                return $this->errorResponse('La especilidad ya fue asignada al médico.');
            }

            $ficha_medica_antecedente = new FichaMedicaAntecedente();
            $ficha_medica_antecedente->ficha_medica_id = $request->id;
            $ficha_medica_antecedente->antecedente_id = $antecedente->id;
            $ficha_medica_antecedente->save();

            DB::commit();
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(FichaMedicaAntecedente $ficha_medica_antecedente)
    {
        try {
            $ficha_medica_antecedente->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
