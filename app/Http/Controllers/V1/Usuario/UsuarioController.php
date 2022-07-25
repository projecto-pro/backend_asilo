<?php

namespace App\Http\Controllers\V1\Usuario;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class UsuarioController extends ApiController
{
    private $controlador_principal = 'UsuarioController';

    public function index()
    {
        return $this->showAll(Usuario::todos()->get());
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $persona = $this->persona($request);
            $data['admin'] = Usuario::USUARIO_REGULAR;
            $data['persona_id'] = $persona->id;
            $data['password'] = base64_decode($request->password);
            $data['sistema'] = Usuario::SISTEMA[0];
            $user = Usuario::create($data);
            $this->asignar_roles($request->roles, $user->id);
            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function cambiar_password(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Usuario::find($request->id);
            $user->password = base64_decode($request->password);
            $user->save();
            DB::commit();

            return $this->successResponse('Contraseña actualizada.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@cambiar_password");
            return $this->errorResponse('Error en el controlador', 423);
        }
    }

    public function update(Request $request, Usuario $user)
    {
        try {
            DB::beginTransaction();
            $persona = $this->persona($request);
            $user->cui = $persona->cui;
            $user->save();

            DB::commit();
            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@update");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy($user)
    {
        try {
            DB::beginTransaction();
            $user = Usuario::withTrashed()->find($user);
            if (is_null($user->deleted_at)) {
                $user->delete();
                $message = 'descativado';
            } else {
                $user->restore();
                $message = 'activado';
            }

            DB::commit();
            return $this->successResponse("Registro {$message}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
