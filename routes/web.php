<?php

use App\Http\Controllers\StorefrontController;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

Route::get('/', function (Request $request) {
    if (in_array($request->getHost(), config('tenancy.central_domains'))) {
        return inertia('Marketing/Home');
    }

    return app(Pipeline::class)
        ->send($request)
        ->through([InitializeTenancyByDomain::class])
        ->then(fn () => app(StorefrontController::class)->index($request));
})->name('home');
