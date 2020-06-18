<?php

    use Illuminate\Support\Facades\Route;

    # Determina que as rotas de autenticação não receberão registrador
    Auth::routes(['register' => false]);
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
    }); // Route::middleware(['auth'])->group(function(){ ... });

