<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\DemandanteController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\TipoContratoController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::get('auth/me', [AuthController::class, 'me'])->middleware('auth:api'); // Para obtener datos del usuario autenticado
Route::put('auth/me', [AuthController::class, 'updateMe'])->middleware('auth:api'); // Para actualizar datos del usuario autenticado

Route::get('empresas', [EmpresaController::class, 'index']);
Route::get('empresas/{id}', [EmpresaController::class, 'show']);
Route::post('empresas', [EmpresaController::class, 'store']);
Route::put('empresas/{id}', [EmpresaController::class, 'update']);
Route::delete('empresas/{id}', [EmpresaController::class, 'destroy']);

Route::get('empresas/{id}/ofertas', [EmpresaController::class, 'ofertas']);
Route::post('empresas/{id}/ofertas', [EmpresaController::class, 'createOferta']);

Route::get('ofertas/{id}', [OfertaController::class, 'show']);
Route::put('ofertas/{id}', [OfertaController::class, 'update']);
Route::delete('ofertas/{id}', [OfertaController::class, 'destroy']);

Route::get('ofertas/{id}/inscripciones', [OfertaController::class, 'inscripciones']);
Route::post('ofertas/{id}/inscripciones', [OfertaController::class, 'inscribirDemandante']);
Route::delete('ofertas/{id}/inscripciones', [OfertaController::class, 'desinscribirDemandante']);

Route::get('ofertas/{id}/candidatos-perfil', [OfertaController::class, 'candidatosPerfil']);
Route::post('ofertas/{id}/adjudicaciones', [OfertaController::class, 'adjudicarCandidato']);
Route::put('ofertas/{id}/cerrar', [OfertaController::class, 'cerrarOferta']);

Route::post('demandantes', [DemandanteController::class, 'store']);
Route::get('demandantes', [DemandanteController::class, 'index']);
Route::get('demandantes/{id}', [DemandanteController::class, 'show']);
Route::put('demandantes/{id}', [DemandanteController::class, 'update']);
Route::put('demandantes/{id}/titulos', [DemandanteController::class, 'updateTitulos']);
Route::get('demandantes/{id}/inscripciones', [DemandanteController::class, 'inscripciones']);

Route::get('ofertas', [DemandanteController::class, 'ofertas']);
Route::get('ofertas/titulacion/{tituloId}', [DemandanteController::class, 'ofertasPorTitulacion']);
Route::delete('demandantes/{id}', [DemandanteController::class, 'destroy']);

Route::post('titulos', [TituloController::class, 'store']);
Route::get('titulos', [TituloController::class, 'index']);
Route::get('titulos/{id}', [TituloController::class, 'show']);
Route::put('titulos/{id}', [TituloController::class, 'update']);
Route::delete('titulos/{id}', [TituloController::class, 'destroy']);

Route::post('tipos-contrato', [TipoContratoController::class, 'store']);
Route::get('tipos-contrato', [TipoContratoController::class, 'index']);
Route::get('tipos-contrato/{id}', [TipoContratoController::class, 'show']);
Route::put('tipos-contrato/{id}', [TipoContratoController::class, 'update']);
Route::delete('tipos-contrato/{id}', [TipoContratoController::class, 'destroy']);

Route::get('centro/empresas/pendientes', [AdminController::class, 'empresasPendientes']);
Route::put('centro/empresas/{id}/validar', [AdminController::class, 'validarEmpresa']);