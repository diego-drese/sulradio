<?php

Route::group(['prefix' => 'console', 'middleware' => ['web', 'auth', 'Oka6\Admin\Http\Middleware\MiddlewareAdmin']], function () {
	Route::get('/emissora', 'Oka6\SulRadio\Http\Controllers\EmissoraController@index')->name('emissora.index')->where(['iconAdmin' => 'mdi mdi-radio', 'menuAdmin' => "Emissoras", 'parentRouteNameAdmin' => 'Sulradio', 'nameAdmin' => 'Emissoras', 'isDefaultAdmin' => '1']);
	Route::get('/emissora/create', 'Oka6\SulRadio\Http\Controllers\EmissoraController@create')->name('emissora.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras novo',]);
	Route::post('/emissora', 'Oka6\SulRadio\Http\Controllers\EmissoraController@store')->name('emissora.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Emissoras , salva novo']);
	Route::get('/emissora/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraController@edit')->name('emissora.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras editar']);
	Route::post('/emissora/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraController@update')->name('emissora.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Emissoras, salva edição']);
	
	Route::get('/emissora-atos-oficiais/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@index')->name('emissora.atos.oficiais.index')->where(['iconAdmin' => 'fas fa-file', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos oficiais emissoras']);
	Route::get('/emissora-atos-oficiais/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@create')->name('emissora.atos.oficiais.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.atos.oficiais.index', 'nameAdmin' => 'Atos oficiais emissoras, adicionar',]);
	Route::post('/emissora-atos-oficiais/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@store')->name('emissora.atos.oficiais.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Atos oficiais emissoras , salva novo']);
	Route::get('/emissora-atos-oficiais/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@edit')->name('emissora.atos.oficiais.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.atos.oficiais.index', 'nameAdmin' => 'Atos oficiais emissoras, editar']);
	Route::post('/emissora-atos-oficiais/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosOficiaisController@update')->name('emissora.atos.oficiais.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.atos.oficiais.index', 'nameAdmin' => 'Atos oficiais emissoras, salva edição']);
	
	Route::get('/emissora.comercial/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@index')->name('emissora.atos.comercial.index')->where(['iconAdmin' => 'fas fa-file-alt', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Atos junta comercial emissoras']);
	Route::get('/emissora.comercial/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@create')->name('emissora.atos.comercial.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.atos.comercial.index', 'nameAdmin' => 'Atos junta comercial emissoras, adicionar',]);
	Route::post('/emissora.comercial/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@store')->name('emissora.atos.comercial.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Atos junta comercial emissoras , salva novo']);
	Route::get('/emissora.comercial/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@edit')->name('emissora.atos.comercial.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.atos.comercial.index', 'nameAdmin' => 'Atos junta comercial emissoras, editar']);
	Route::post('/emissora.comercial/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraAtosComerciaisController@update')->name('emissora.atos.comercial.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.atos.comercial.index', 'nameAdmin' => 'Atos junta comercial emissoras, salva edição']);
	
	Route::get('/emissora-processo/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@index')->name('emissora.processo.index')->where(['iconAdmin' => 'fas fa-folder', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Processos emissoras']);
	Route::get('/emissora-processo/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@create')->name('emissora.processo.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.processo.index', 'nameAdmin' => 'Processos emissoras, adicionar',]);
	Route::post('/emissora-processo/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@store')->name('emissora.processo.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Processos emissoras , salva novo']);
	Route::get('/emissora-processo/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@edit')->name('emissora.processo.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.processo.index', 'nameAdmin' => 'Processos emissoras, editar']);
	Route::post('/emissora-processo/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraProcessoController@update')->name('emissora.processo.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.processo.index', 'nameAdmin' => 'Processos emissoras, salva edição']);
	
	Route::get('/emissora-endereco/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@index')->name('emissora.endereco.index')->where(['iconAdmin' => 'fas fa-map-marker-alt', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Endereços emissoras']);
	Route::get('/emissora-endereco/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@create')->name('emissora.endereco.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.endereco.index', 'nameAdmin' => 'Endereços emissoras, adicionar',]);
	Route::post('/emissora-endereco/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@store')->name('emissora.endereco.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Endereços emissoras , salva novo']);
	Route::get('/emissora-endereco/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@edit')->name('emissora.endereco.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.endereco.index', 'nameAdmin' => 'Endereços emissoras, editar']);
	Route::post('/emissora-endereco/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraEnderecoController@update')->name('emissora.endereco.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.endereco.index', 'nameAdmin' => 'Endereços emissoras, salva edição']);
	
	Route::get('/emissora-contato/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraContatoController@index')->name('emissora.contato.index')->where(['iconAdmin' => 'fas fa-map-marker-alt', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Contatos emissoras']);
	Route::get('/emissora-contato/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraContatoController@create')->name('emissora.contato.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.contato.index', 'nameAdmin' => 'Contatos emissoras, adicionar',]);
	Route::post('/emissora-contato/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraContatoController@store')->name('emissora.contato.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Contatos emissoras , salva novo']);
	Route::get('/emissora-contato/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraContatoController@edit')->name('emissora.contato.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.contato.index', 'nameAdmin' => 'Contatos emissoras, editar']);
	Route::post('/emissora-contato/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraContatoController@update')->name('emissora.contato.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.contato.index', 'nameAdmin' => 'Contatos emissoras, salva edição']);
	
	Route::get('/emissora-socio/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraSocioController@index')->name('emissora.socio.index')->where(['iconAdmin' => 'fas fa-user', 'parentRouteNameAdmin' => 'emissora.index', 'nameAdmin' => 'Sócios emissoras']);
	Route::get('/emissora-socio/{emissoraID}/create', 'Oka6\SulRadio\Http\Controllers\EmissoraSocioController@create')->name('emissora.socio.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'emissora.socio.index', 'nameAdmin' => 'Sócios emissoras, adicionar',]);
	Route::post('/emissora-socio/{emissoraID}', 'Oka6\SulRadio\Http\Controllers\EmissoraSocioController@store')->name('emissora.socio.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Sócios emissoras , salva novo']);
	Route::get('/emissora-socio/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraSocioController@edit')->name('emissora.socio.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.socio.index', 'nameAdmin' => 'Sócios emissoras, editar']);
	Route::post('/emissora-socio/{emissoraID}/{id}', 'Oka6\SulRadio\Http\Controllers\EmissoraSocioController@update')->name('emissora.socio.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'emissora.socio.index', 'nameAdmin' => 'Sócios emissoras, salva edição']);
	
	Route::get('/anatel/emissoras', 'Oka6\SulRadio\Http\Controllers\AnatelController@emissoras')->name('anatel.emissoras')->where(['iconAdmin' => 'mdi mdi-radio-tower', 'menuAdmin' => "Emissoras2", 'parentRouteNameAdmin' => 'anatel.emissoras', 'nameAdmin' => 'Emissoras']);
	Route::get('/anatel/emissora/modal/{_id}', 'Oka6\SulRadio\Http\Controllers\AnatelController@emissoraModal')->name('anatel.emissora.modal')->where(['iconAdmin' => 'mdi mdi-radio-tower', 'parentRouteNameAdmin' => 'anatel.emissoras', 'nameAdmin' => 'Emissora modal']);
	
	/** New System */
	Route::get('/plan', 'Oka6\SulRadio\Http\Controllers\PlanController@index')->name('plan.index')->where(['iconAdmin' => 'mdi mdi-parking', 'parentRouteNameAdmin' => 'Sulradio', 'menuAdmin' => "Planos", 'nameAdmin' => 'Planos', 'isDefaultAdmin' => '1']);
	Route::get('/plan/create', 'Oka6\SulRadio\Http\Controllers\PlanController@create')->name('plan.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'plan.index', 'nameAdmin' => 'Planos novo',]);
	Route::post('/plan', 'Oka6\SulRadio\Http\Controllers\PlanController@store')->name('plan.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Planos , salva novo']);
	Route::get('/plan/{id}', 'Oka6\SulRadio\Http\Controllers\PlanController@edit')->name('plan.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'plan.index', 'nameAdmin' => 'Planos editar']);
	Route::post('/plan/{id}', 'Oka6\SulRadio\Http\Controllers\PlanController@update')->name('plan.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'plan.index', 'nameAdmin' => 'Planos, salva edição']);
	
	Route::get('/document-type', 'Oka6\SulRadio\Http\Controllers\DocumentTypeController@index')->name('document.type.index')->where(['iconAdmin' => 'mdi mdi-file-document', 'parentRouteNameAdmin' => 'Sulradio', 'menuAdmin' => "Tipos de documentos", 'nameAdmin' => 'Tipos de documentos', 'isDefaultAdmin' => '1']);
	Route::get('/document-type/create', 'Oka6\SulRadio\Http\Controllers\DocumentTypeController@create')->name('document.type.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'document.type.index', 'nameAdmin' => 'Tipos de documentos novo',]);
	Route::post('/document-type', 'Oka6\SulRadio\Http\Controllers\DocumentTypeController@store')->name('document.type.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Tipos de documentos , salva novo']);
	Route::get('/document-type/{id}', 'Oka6\SulRadio\Http\Controllers\DocumentTypeController@edit')->name('document.type.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'document.type.index', 'nameAdmin' => 'Tipos de documentos editar']);
	Route::post('/document-type/{id}', 'Oka6\SulRadio\Http\Controllers\DocumentTypeController@update')->name('document.type.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'plan.index', 'nameAdmin' => 'Tipos de documentos, salva edição']);
	
	Route::get('/client', 'Oka6\SulRadio\Http\Controllers\ClientController@index')->name('client.index')->where(['iconAdmin' => 'mdi mdi-ticket-account', 'parentRouteNameAdmin' => 'Sulradio', 'menuAdmin' => "Clientes", 'nameAdmin' => 'Clientes', 'isDefaultAdmin' => '1']);
	Route::get('/client/broadcast/', 'Oka6\SulRadio\Http\Controllers\ClientController@index')->name('client.index')->where(['iconAdmin' => 'mdi mdi-ticket-account', 'parentRouteNameAdmin' => 'Sulradio', 'menuAdmin' => "Clientes", 'nameAdmin' => 'Clientes', 'isDefaultAdmin' => '1']);
	Route::get('/client/create', 'Oka6\SulRadio\Http\Controllers\ClientController@create')->name('client.create')->where(['iconAdmin' => 'fas fa-plus-square', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes novo',]);
	Route::post('/client', 'Oka6\SulRadio\Http\Controllers\ClientController@store')->name('client.store')->where(['iconAdmin' => 'fas fa-plus-square', 'nameAdmin' => 'Clientes , salva novo']);
	Route::get('/client/{id}', 'Oka6\SulRadio\Http\Controllers\ClientController@edit')->name('client.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes editar']);
	Route::post('/client/{id}', 'Oka6\SulRadio\Http\Controllers\ClientController@update')->name('client.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, salva edição']);
	
	Route::get('/client/{id}/payment', 'Oka6\SulRadio\Http\Controllers\ClientController@payment')->name('client.payment')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, pagamentos']);
	Route::get('/client/{id}/payment/add', 'Oka6\SulRadio\Http\Controllers\ClientController@paymentAdd')->name('client.payment.add')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, adiciona pagamento']);
	Route::get('/client/{id}/payment/reverse', 'Oka6\SulRadio\Http\Controllers\ClientController@paymentReverse')->name('client.payment.reverse')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, estorna pagamento']);
	
	Route::get('/client/{id}/user', 'Oka6\SulRadio\Http\Controllers\ClientController@user')->name('client.user')->where(['iconAdmin' => 'fas fa-users', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, usuários']);
	Route::get('/client/{id}/user/create', 'Oka6\SulRadio\Http\Controllers\ClientController@userCreate')->name('client.user.create')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.user', 'nameAdmin' => 'Clientes, adiciona usuário']);
	Route::post('/client/{id}/user/store', 'Oka6\SulRadio\Http\Controllers\ClientController@userStore')->name('client.user.store')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.user', 'nameAdmin' => 'Clientes, salva usuário']);
	Route::get('/client/{id}/user/{idUser}', 'Oka6\SulRadio\Http\Controllers\ClientController@userEdit')->name('client.user.edit')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.user', 'nameAdmin' => 'Clientes, edita usuário']);
	Route::post('/client/{id}/user/{idUser}/update', 'Oka6\SulRadio\Http\Controllers\ClientController@userUpdate')->name('client.user.update')->where(['iconAdmin' => 'fas fa-edit', 'parentRouteNameAdmin' => 'client.user', 'nameAdmin' => 'Clientes, atualiza usuário']);
	
	Route::get('/client/{id}/broadcast', 'Oka6\SulRadio\Http\Controllers\ClientController@broadcast')->name('client.broadcast')->where(['iconAdmin' => 'mdi mdi-radio', 'parentRouteNameAdmin' => 'client.index', 'nameAdmin' => 'Clientes, emissoras']);
});

Route::group(['prefix' => 'console', 'middleware' => ['web', 'auth']], function () {
	Route::any('/city/search', 'Oka6\SulRadio\Http\Controllers\PublicController@searchCity')->name('city.search');
	Route::any('/broadcast/search', 'Oka6\SulRadio\Http\Controllers\PublicController@searchBroadcast')->name('broadcast.search');
});



