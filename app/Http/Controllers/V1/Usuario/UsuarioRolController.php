<?php

namespace App\Http\Controllers\V1\Usuario;

use App\Models\RolMenu;
use App\Models\Usuario;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class UsuarioRolController extends ApiController
{
    private $controlador_principal = 'UsuarioRolController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->asignar_roles($request->roles, $request->id);
            DB::commit();
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function show(Usuario $user_rol)
    {
        try {
            $rols = UsuarioRol::select('rol_id')->where('usuario_id', $user_rol->id)->distinct('rol_id')->pluck('rol_id');
            $menus = RolMenu::select('menu_id')->whereIn('rol_id', $rols)->distinct('menu_id')->with('menu')->get();
            return $this->successResponse($menus);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(UsuarioRol $user_rol)
    {
        try {
            DB::beginTransaction();
            $user_rol->delete();
            DB::commit();
            return $this->successResponse('Registro desactivado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }

            return $this->errorResponse('Error en el controlador');
        }
    }
}
