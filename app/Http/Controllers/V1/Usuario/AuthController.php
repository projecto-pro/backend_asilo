<?php

namespace App\Http\Controllers\V1\Usuario;

use App\Models\Rol;
use GuzzleHttp\Client;
use App\Models\UsuarioRol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Illuminate\Auth\Authenticatable;

class AuthController extends ApiController
{
    use Authenticatable;

    public function __construct()
    {
        $this->middleware('auth:passport')->except(['login']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'cui'       => 'required|numeric|digits_between:13,15|exists:usuario,cui',
            'password'    => 'required|string',
        ], [
            'cui.required'       => 'El CUI es obligatorio',
            'cui.exists'       => 'El usuario no existe',
            'cui.numeric'       => 'El solo debe contener números',
            'cui.digits_between'       => 'El CUI debe de tener como mínimo :min y máximo :max dígitos',

            'password.required'       => 'La contraseña es obligatoria'
        ]);

        try {
            if (!Auth::attempt(['cui' => $request->cui, 'password' => $request->password, 'deleted_at' => null])) {
                return response()->json([
                    'error' => 'Las credenciales de acceso no son correctas, vuelva a intentar lo.', 'code' => '401'
                ], 401);
            }

            $http = new Client(
                [
                    'verify' => false
                ]
            );
            $response = $http->post(config('services.passport.base_url') . 'servicio/passport/generar/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request->cui,
                    'password' => $request->password,
                    'scope' => '*',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@login");
            return $this->errorResponse($e->getMessage());
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $request->user();
            $buscar_roles = UsuarioRol::select('rol_id')->where('usuario_id', $user->id)->distinct('rol_id')->pluck('rol_id');
            $roles = Rol::whereIn('id', $buscar_roles)->pluck('nombre');

            return $this->successResponse(['user' => $user->empleado, 'roles' => $roles]);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@me");
            return $this->errorResponse($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return $this->showMessage("saliendo...", 200);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@logout");
            return $this->errorResponse($e->getMessage());
        }
    }
}
