<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//rutas para AuthController
Route::name('me')->get('auth/me', 'Usuario\AuthController@me');
Route::name('login')->post('auth/login', 'Usuario\AuthController@login');
Route::name('logout')->get('auth/logout', 'Usuario\AuthController@logout');

//rutas para UsuarioController
Route::resource('user', 'Usuario\UsuarioController')->except('create', 'edit', 'show');
Route::name('user.password')->post('user_password', 'Usuario\UsuarioController@cambiar_password');

//rutas para UsuarioRolController
Route::resource('user_rol', 'Usuario\UsuarioRolController')->except('index', 'create', 'edit', 'update');

//rutas para RolController
Route::resource('rol', 'Rol\RolController')->except('create', 'edit', 'show', 'update');

//rutas para RolMenuController
Route::resource('rol_menu', 'Rol\RolMenuController')->except('index', 'create', 'edit', 'show', 'update');
Route::name('rol_menu.eliminar_masivo')->post('rol_menu/eliminar_masivo', 'Rol\RolMenuController@eliminario_masiva');

//rutas para MenuController
Route::resource('menu', 'Menu\MenuController')->only('index');

//rutas para SelectController
Route::name('select.log_select')->get('select/log', 'Catalogo\SelectController@log_select');
Route::name('select.departamento_select')->get('select/departamento', 'Catalogo\SelectController@departamento_select');
Route::name('select.municipio_select')->get('select/municipio', 'Catalogo\SelectController@municipio_select');
Route::name('select.presentacion_select')->get('select/presentacion', 'Catalogo\SelectController@presentacion_select');
Route::name('select.cliente_proveedor_select')->get('select/cliente', 'Catalogo\SelectController@cliente_proveedor_select');
Route::name('select.persona_select')->get('select/persona', 'Catalogo\SelectController@persona_select');
Route::name('select.mes_select')->get('select/mes', 'Catalogo\SelectController@mes_select');
Route::name('select.tipo_ingreso_select')->get('select/tipo_ingreso', 'Catalogo\SelectController@tipo_ingreso_select');
Route::name('select.protegido_select')->get('select/protegido', 'Catalogo\SelectController@protegido_select');
Route::name('select.especialidad_select')->get('select/especialidad', 'Catalogo\SelectController@especialidad_select');
Route::name('select.examen_select')->get('select/examen', 'Catalogo\SelectController@examen_select');
Route::name('select.medicina_select')->get('select/medicina', 'Catalogo\SelectController@medicina_select');
Route::name('select.medico_select')->get('select/medico', 'Catalogo\SelectController@medico_select');
Route::name('select.ingreso_asilo_select')->get('select/ingreso_asilo', 'Catalogo\SelectController@ingreso_asilo_select');
Route::name('select.antecedente_select')->get('select/antecedente', 'Catalogo\SelectController@antecedente_select');
Route::name('select.consulta_medica_select')->get('select/consulta_medica', 'Catalogo\SelectController@consulta_medica_select');

//rutas para PresentacionController
Route::resource('presentacion', 'Catalogo\PresentacionController')->only(['store', 'destroy']);

//rutas para IngresoController
Route::resource('ingreso_asilo', 'Ingreso\IngresoController')->only(['index', 'show', 'store', 'destroy']);

//rutas para ProtegidoController
Route::resource('protegido', 'Ingreso\ProtegidoController')->only(['index', 'show']);

//rutas para MensualidadController
Route::resource('mensualidad', 'Administracion\MensualidadController')->only(['edit', 'show']);

//rutas para MedicinaController
Route::resource('medicina', 'Farmacia\MedicinaController')->only(['index', 'store', 'update', 'destroy']);

//rutas para MedicinaPresentacionController
Route::resource('medicina_presentacion', 'Farmacia\MedicinaPresentacionController')->only(['store', 'update']);

//rutas para MedicoController
Route::resource('medico', 'Medico\MedicoController')->only(['index', 'store', 'update', 'destroy']);

//rutas para MedicoEspecialidadController
Route::resource('asinar_especialidad', 'Medico\MedicoEspecialidadController')->only(['store', 'destroy']);

//rutas para ConsultaMedicaController
Route::resource('consulta_medica', 'Consulta\ConsultaMedicaController')->only(['index', 'show', 'store', 'destroy']);
Route::name('consulta_medica.atendido')->get('consulta_medica/atendido/{consulta_medica}', 'Consulta\ConsultaMedicaController@atendido');

//rutas para EvolucionMedicaController
Route::resource('evolucion_medica', 'Consulta\EvolucionMedicaController')->only(['show', 'store']);

//rutas para HistorialMedicoController
Route::resource('historial_medico', 'Consulta\HistorialMedicoController')->only(['show', 'store', 'destroy']);

//rutas para LaboratorioController
Route::resource('laboratorio', 'Consulta\LaboratorioController')->only(['index', 'show', 'destroy']);

//rutas para SolicitudMedicaController
Route::resource('solicitud_medica', 'Consulta\SolicitudMedicaController')->only(['index', 'show', 'store', 'destroy']);

//rutas para TratamientoController
Route::resource('tratamiento', 'Consulta\TratamientoController')->only(['index', 'edit', 'show', 'destroy']);

//rutas para ExamenRealizadoController
Route::resource('examen_realizado', 'Consulta\ExamenRealizadoController')->only(['store']);

//rutas para FichaMedicaController
Route::resource('ficha_medica', 'FichaMedica\FichaMedicaController')->only(['show', 'store']);

//rutas para FichaMedicaAntecedenteController
Route::resource('ficha_medica_antecedente', 'FichaMedica\FichaMedicaAntecedenteController')->only(['store', 'destroy']);
