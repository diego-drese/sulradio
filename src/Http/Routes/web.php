<?php

Route::group(['prefix' => 'console', 'middleware' => ['web', 'auth', 'Oka6\Admin\Http\Middleware\MiddlewareAdmin']], function () {
	Route::get('/emissora', 'Oka6\SulRadio\Http\Controllers\EmissoraController@index')->name('emissora.index')->where(['iconAdmin' => 'mdi mdi-radio', 'menuAdmin' => "Emissoras", 'parentRouteNameAdmin' => 'Sulradio', 'nameAdmin' => 'Emissoras', 'isDefaultAdmin' => '1']);
	Route::get('/emissora/create', 'Oka6\SulRadio\Http\Controllers\EmissoraController@create')->name('emissora.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras novo',]);
	Route::post('/emissora', 'Oka6\SulRadio\Http\Controllers\EmissoraController@store')->name('emissora.store')->where(['iconAdmin' => 'fas fa-floppy-o', 'nameAdmin' => 'Emissoras salva novo']);
	Route::get('/emissora/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraController@edit')->name('emissora.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras edita']);
	Route::post('/emissora/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraController@update')->name('emissora.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras salva edição']);
	
	Route::get('/emissora-atos-oficiais/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@index')->name('emissora.atos.oficiais.index')->where(['iconAdmin' => 'fas fa-file', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos oficiais emissoras']);
	Route::get('/emissora-atos-oficiais/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@create')->name('emissora.atos.oficiais.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos oficiais emissoras novo',]);
	Route::post('/emissora-atos-oficiais/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@store')->name('emissora.atos.oficiais.store')->where(['iconAdmin' => 'fas fa-floppy-o', 'nameAdmin' => 'Atos oficiais emissoras salva novo']);
	Route::get('/emissora-atos-oficiais/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@edit')->name('emissora.atos.oficiais.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos oficiais emissoras edit']);
	Route::post('/emissora-atos-oficiais/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@update')->name('emissora.atos.oficiais.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos oficiais emissoras salva edição']);

});



