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
            Route::post('/lista','RequestData@index')->name('list');
        });



        // Rotas para consulta de tarefas de solicitações de serviço
        Route::prefix('task')->name('task.')->group(function(){
            // [task.list]
            Route::post('/lista','Task@list')->name('list');
        });

        

        // Rotas para consulta de dados
        Route::prefix('util')->name('util.')->group(function(){
            // [util.filtro] - Dados para filtro
            Route::any('/filtro','Util@filtroData')->name('filtro');
            // [util.tipo] - Dados do tipo
            Route::any('/tipo','Util@filtroTipo')->name('tipo');
            // [util.questao] - dados para preenchimento
            Route::any('/questao','Util@filtroQuestao')->name('questao');
        });

        

        Route::prefix('admin')->name('admin.')->group(function(){
            // [admin.user] - 
            Route::post('/usuario','Admin@usuario')->name('usuario');
        });
    });
