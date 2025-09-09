<?php

use App\Http\Controllers\Auth\MenuController;
use Illuminate\Support\Facades\Route;

Route::prefix('menu')->name('menu.')->group(function(){
    Route::post('/insert',[App\Http\Controllers\Auth\MenuController::class, 'insert'])->name('insert');
    Route::post('/update/{uuid}',[App\Http\Controllers\Auth\MenuController::class, 'update'])->name('update');
    Route::get('/datatable',[App\Http\Controllers\Auth\MenuController::class, 'datatable'])->name('datatable');
    Route::delete('/delete/{uuid}',[App\Http\Controllers\Auth\MenuController::class, 'destroy'])->name('delete');
});

Route::prefix('role')->name('role.')->group(function(){
    Route::post('/insert',[App\Http\Controllers\Auth\RoleController::class, 'insert'])->name('insert');
    Route::post('/update/{uuid}',[App\Http\Controllers\Auth\RoleController::class, 'update'])->name('update');
    Route::get('/datatable',[App\Http\Controllers\Auth\RoleController::class, 'datatable'])->name('datatable');
    Route::delete('/delete/{uuid}',[App\Http\Controllers\Auth\RoleController::class, 'destroy'])->name('delete');
    Route::get('/get_menu',[App\Http\Controllers\Auth\RoleController::class, 'get_menu'])->name('get_menu');
    Route::delete('/destroy_menu/{uuid}',[App\Http\Controllers\Auth\RoleController::class, 'destroy_menu'])->name('delete_menu');
    Route::post('/insert_menu',[App\Http\Controllers\Auth\RoleController::class, 'insert_menu'])->name('insert_menu');
    Route::get('/get_user',[App\Http\Controllers\Auth\RoleController::class, 'get_user'])->name('get_user');
    Route::delete('/destroy_user/{uuid}',[App\Http\Controllers\Auth\RoleController::class, 'destroy_user'])->name('delete_user');
    Route::post('/insert_role',[App\Http\Controllers\Auth\RoleController::class, 'insert_role'])->name('insert_role');
    Route::get('/get_user_role/{id}',[App\Http\Controllers\Auth\RoleController::class, 'get_user_role'])->name('get_user_role');
});

Route::prefix('user')->name('user.')->group(function(){
    Route::post('/insert',[App\Http\Controllers\Auth\UsersController::class, 'insert'])->name('insert');
    Route::post('/update/{id}',[App\Http\Controllers\Auth\UsersController::class, 'update'])->name('update');
    Route::get('/datatable',[App\Http\Controllers\Auth\UsersController::class, 'datatable'])->name('datatable');
    Route::delete('/delete/{id}',[App\Http\Controllers\Auth\UsersController::class, 'destroy'])->name('delete');
    Route::get('/get_data',[App\Http\Controllers\Auth\UsersController::class, 'get_data'])->name('get_data');
});