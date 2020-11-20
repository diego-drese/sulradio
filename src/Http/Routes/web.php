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
	
	Route::get('/emissora.comercial/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@index')->name('emissora.atos.comercial.index')->where(['iconAdmin' => 'fas fa-file-alt', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos junta comercial emissoras']);
	Route::get('/emissora.comercial/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@create')->name('emissora.atos.comercial.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos junta comercial emissoras novo',]);
	Route::post('/emissora.comercial/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@store')->name('emissora.atos.comercial.store')->where(['iconAdmin' => 'fas fa-floppy-o', 'nameAdmin' => 'Atos junta comercial emissoras salva novo']);
	Route::get('/emissora.comercial/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@edit')->name('emissora.atos.comercial.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos junta comercial emissoras edit']);
	Route::post('/emissora.comercial/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@update')->name('emissora.atos.comercial.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos junta comercial emissoras salva edição']);
	
	Route::get('/emissora-processo/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@index')->name('emissora.processo.index')->where(['iconAdmin' => 'fas fa-folder', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Processos emissoras']);
	Route::get('/emissora-processo/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@create')->name('emissora.processo.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Processos emissoras novo',]);
	Route::post('/emissora-processo/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@store')->name('emissora.processo.store')->where(['iconAdmin' => 'fas fa-floppy-o', 'nameAdmin' => 'Processos emissoras salva novo']);
	Route::get('/emissora-processo/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@edit')->name('emissora.processo.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Processos emissoras edit']);
	Route::post('/emissora-processo/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@update')->name('emissora.processo.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Processos emissoras salva edição']);
	
	Route::get('/emissora-endereco/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@index')->name('emissora.endereco.index')->where(['iconAdmin' => 'fas fa-map-marker-alt', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Endereços emissoras']);
	Route::get('/emissora-endereco/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@create')->name('emissora.endereco.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Endereços emissoras novo',]);
	Route::post('/emissora-endereco/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@store')->name('emissora.endereco.store')->where(['iconAdmin' => 'fas fa-floppy-o', 'nameAdmin' => 'Endereços emissoras salva novo']);
	Route::get('/emissora-endereco/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@edit')->name('emissora.endereco.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Endereços emissoras edit']);
	Route::post('/emissora-endereco/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@update')->name('emissora.endereco.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Endereços emissoras salva edição']);
	
});



