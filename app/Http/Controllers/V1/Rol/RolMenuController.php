<?php

namespace App\Http\Controllers\V1\Rol;

use App\Models\RolMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class RolMenuController extends ApiController
{
    private $controlador_principal = 'RolMenuController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $this->asignar_menus($request->menus_id, $request->id);
            DB::commit();

            return $this->successResponse('Registro agregado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(RolMenu $rol_menu)
    {
        try {
            $rol_menu->forceDelete();
            return $this->successResponse('Registro desactivado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }

    public function eliminario_masiva(Request $request)
    {
        try {

            DB::beginTransaction();
            foreach ($request->eliminar as $value) {
                RolMenu::find($value['id'])->forceDelete();
            }
            DB::commit();

            return $this->successResponse('Registro desactivado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@eliminario_masiva");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
