<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Contato;
use Oka6\SulRadio\Models\ContatoInfo;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Emissoracontato;
use Oka6\SulRadio\Models\EmissoraTipocontato;
use Oka6\SulRadio\Models\Funcao;
use Yajra\DataTables\DataTables;

class EmissoraContatoController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = Contato::query()
				->withEmissora($emissoraID)
				->withFuncao();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.contato.edit', [$row->emissoraID, $row->contatoID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-contato.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-contato.create', ['data' => $data, 'emissoraID' => $emissoraID, 'contatos'=>[]]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'nome_contato' => 'required',
			'funcaoID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		
		$contato = Contato::create($dataForm);
		ContatoInfo::insertOrupdateContacts($request, $contato->contatoID);
		toastr()->success('Contato Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.edit', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = Contato::getById($id);
		$contatos = ContatoInfo::getByContactId($data->contatoID);
		return $this->renderView('SulRadio::backend.emissora-contato.edit', ['data' => $data, 'emissoraID' => $emissoraID, 'contatos'=>$contatos]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = Contato::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'nome_contato' => 'required',
			'funcaoID' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		ContatoInfo::insertOrupdateContacts($request, $data->contatoID);
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.edit', $emissoraID));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.contato.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.contato.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.contato.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.contato.update', [1, 1]),
			'funcao' => Funcao::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
	
}