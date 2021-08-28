<?php

Route::prefix('2fa')->name('2fa.')->middleware('auth')->group(function () {
    Route::get('setup', 'SetupSecret@create')->name('setup');
    Route::post('setup', 'SetupSecret@store')->name('store');

    Route::get('edit', 'SetupSecret@edit')->name('edit');
    Route::post('edit', 'SetupSecret@update')->name('update');
    Route::delete('remove', 'SetupSecret@destroy')->name('destroy');

    Route::get('provide', 'ProvideToken@create')->name('provide');
    Route::post('provide', 'ProvideToken@store')->name('provided');
});
