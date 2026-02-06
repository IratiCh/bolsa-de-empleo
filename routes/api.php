<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TituloController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\DemandanteController;
use App\Http\Controllers\OfertaDemandanteController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register_demandante', [AuthController::class, 'registerDemandante']);
    Route::post('/register_empresa', [AuthController::class, 'registerEmpresa']);
});

/* CENTRO */
Route::group(['prefix' => 'centro'], function () {
    Route::get('/empresas-pendientes', [AdminController::class, 'getEmpresasPendientes']);
    Route::put('/validar-empresa/{id}', [AdminController::class, 'validarEmpresa']);
    Route::get('/notificaciones', [AdminController::class, 'getNotificaciones']);
    Route::put('/notificaciones/{id}/leida', [AdminController::class, 'marcarNotificacionLeida']);
    Route::put('/notificaciones/leidas', [AdminController::class, 'marcarTodasNotificacionesLeidas']);
    Route::get('/historico-ofertas', [OfertaController::class, 'getHistoricoCentro']);
    Route::get('/titulos', [TituloController::class, 'gestionTitulos']);
    Route::delete('/titulos/{id}', [TituloController::class, 'eliminar']);
    Route::post('/titulos', [TituloController::class, 'crear']);
    Route::get('/titulos/{id}', [TituloController::class, 'mostrar']);
    Route::put('/titulos/{id}', [TituloController::class, 'modificar']);
});

/* EMPRESA */

Route::group(['prefix' => 'empresa'], function() {
    Route::get('/asignar_oferta/{id}', [EmpresaController::class, 'getDemandantes']);
    Route::post('/asignar_oferta/{id}/asignar', [EmpresaController::class, 'asignarDemandante']);
    Route::get('/historico-ofertas', [OfertaController::class, 'getHistoricoEmpresa']);
    Route::get('/ofertas/{id}/solicitudes', [EmpresaController::class, 'getSolicitudesOferta']);
});

/* DEMANDANTE */

Route::group(['prefix' => 'ofertas'], function () {
    
    Route::get('/abiertas', [OfertaController::class, 'getOfertasAbiertas']);
    Route::put('/{id}/cerrar', [OfertaController::class, 'cerrarOferta']);

    Route::get('/tipos-contrato', [OfertaController::class, 'getTiposContrato']);
    Route::get('/titulos', [OfertaController::class, 'getTitulos']);
    Route::post('/crear', [OfertaController::class, 'crearOferta'])->middleware('api');
});

Route::group(['prefix' => 'demandante'], function () {
    Route::get('/ofertas', [OfertaController::class, 'getOfertasDemandante']);
    
    Route::get('/ofertas/{id}', [OfertaDemandanteController::class, 'show']);
    Route::get('/ofertas/{id}/inscrito', [OfertaDemandanteController::class, 'verificarInscripcion']);
    Route::post('/ofertas/{id}/inscribirse', [OfertaDemandanteController::class, 'inscribir']);


    Route::get('/perfil/{id}', [DemandanteController::class, 'getPerfil']);
    Route::put('/actualizar-datos/{id}', [DemandanteController::class, 'actualizarPerfil']);
    Route::post('/guardar-titulos/{id}', [DemandanteController::class, 'guardarTitulo']);
    Route::get('/cv/{id}', [DemandanteController::class, 'getCv']);
    Route::put('/cv-form/{id}', [DemandanteController::class, 'guardarCvForm']);
    Route::post('/cv-pdf/{id}', [DemandanteController::class, 'subirCvPdf']);

    Route::get('/ofertas-inscritas', [OfertaDemandanteController::class, 'ofertasInscritas']);
    Route::delete('/ofertas/{id}/cancelar-inscripcion', [OfertaDemandanteController::class, 'cancelarInscripcion']);

});
