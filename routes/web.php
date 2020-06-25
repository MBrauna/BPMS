<?php

    use Illuminate\Support\Facades\Route;

    # Determina que as rotas de autenticação não receberão registrador
    Auth::routes(['register' => true]);
    # Tudo dentro deste grupo estará sob a intervenção dos middlewares
    Route::middleware(['auth'])->group(function(){

        // Definirá qual será o endereço principal para o projeto
        Route::any('/',function(){
            return redirect()->route('graph.principal');
        })->name('raiz');

        // Para gráficos - Todas as rotas de analíticos e sintéticos
        Route::namespace('graph')->name('graph.')->group(function(){
            # [graph.principal]
            Route::any('/BI','BiGraph@index')->name('principal');
        }); // Route::namespace('Performance')->name('grafico.')->group(function(){ ... });

        // Para Tarefas - Todas as rotas de tarefas
        Route::namespace('task')->name('task.')->group(function(){
            # [task.list]
            Route::any('/tarefa','ListTask@index')->name('list');
        }); // Route::namespace('task')->name('task.')->group(function(){ ... });

        // Para Solicitações - Todas as rotas de solicitações de serviço
        Route::namespace('request')->name('request.')->group(function(){
            # [request.id]
            Route::any('/solicitacao/{id}','IDRequest@index')->name('id');
            # [request.list]
            Route::any('/solicitacao','ListRequest@index')->name('list');
            # [request.index]
            Route::get('/abertura','CreateRequest@index')->name('index');
            Route::post('/abertura','CreateRequest@create')->name('create');
        }); // Route::namespace('request')->name('request.')->group(function(){ ... });
    }); // Route::middleware(['auth'])->group(function(){ ... });

