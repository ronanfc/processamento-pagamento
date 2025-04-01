<?php

    use App\Http\Controllers\PagamentoController;
    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\ProfileController;

    Route::get('/', function () {
        return view('welcome');
    });


    Route::middleware(['auth', 'is_admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    });

    Route::middleware(['auth'])->group(function () {

        Route::prefix('checkout')
            ->name('checkout.')
            ->group(function () {
                Route::get('/', [PagamentoController::class, 'index'])->name('index');
                Route::post('/', [PagamentoController::class, 'pagamento'])->name('pagamento');
            });

        Route::get('/pagamento/{id}', [PagamentoController::class, 'show'])->name('pagamento.show');
        Route::get('/getPixQrCode/{id}', [PagamentoController::class, 'getPixQrCode'])->name('getPixQrCode');

    });



    require __DIR__.'/auth.php';

