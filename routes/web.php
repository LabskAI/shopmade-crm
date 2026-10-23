<?php

use Illuminate\Support\Facades\Route;
use Shopmade\Crm\Http\Controllers\ContactController;

Route::middleware(['web', 'auth', 'can:staff.access'])->group(function () {
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');

    Route::middleware('can-manage-core:contacts')->group(function () {
        Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
        Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
        Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    });

    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
});
