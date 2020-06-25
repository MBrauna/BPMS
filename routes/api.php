<?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });*/

    Route::namespace('API')->group(function(){

        // Rotas para consulta de Solicitações de Serviço
        Route::prefix('request')->name('request.')->group(function(){
            // [request.list]
            Route::any('/lista','RequestData@index')->name('list');
        });

        // Rotas para consulta de dados
        Route::prefix('util')->name('util.')->group(function(){
            // [util.filtro] - Dados para filtro
            Route::post('/filtro','Util@filtroData')->name('filtro');
            // [util.tipo] - Dados do tipo
            Route::post('/tipo','Util@filtroTipo')->name('tipo');
            // [util.questao] - dados para preenchimento
            Route::post('/questao','Util@filtroQuestao')->name('questao');
        });
    });
