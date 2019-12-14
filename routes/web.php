<?php

Route::prefix('2fa')->name('2fa.')->middleware('auth')->group(function () {
    Route::get('setup', 'SetupSecret@create')->name('setup');
    Route::post('setup', 'SetupSecret@store')->name('store');

    Route::get('provide', 'ProvideToken@create')->name('provide');
    Route::post('provide', 'ProvideToken@store')->name('provided');
});
