<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EmissoraEndereco;
use Oka6\SulRadio\Models\EmissoraProcesso;
use Oka6\SulRadio\Models\EmissoraProcessoFase;
use Oka6\SulRadio\Models\EmissoraTipoEndereco;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraEnderecoController extends SulradioController {
	use ValidatesRequests;
	
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = EmissoraEndereco::query()
				->withEmissora($emissoraID)
				->withEndereco()
				->withTipoEndereco();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.endereco.edit', [$row->emissoraID, $row->enderecoID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-endereco.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-endereco.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_enderecoID' => 'required',
			'logradouro' => 'required',
			'ufID' => 'required',
			'municipioID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		
		EmissoraEndereco::create($dataForm);
		toastr()->success('Enrdeço Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.endereco.index', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = EmissoraEndereco::getById($id);
		$data->ufID = null;
		$ufID = Municipio::getById($data->municipioID);
		if ($ufID) {
			$data->ufID = $ufID->ufID;
		}
		return $this->renderView('SulRadio::backend.emissora-endereco.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = EmissoraEndereco::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_enderecoID' => 'required',
			'logradouro' => 'required',
			'ufID' => 'required',
			'municipioID' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.endereco.index', [$emissoraID]));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.update', [1, 1]),
			'tipoEndereco' => EmissoraTipoEndereco::getWithCache(),
			'uf' => Uf::getWithCache(),
			'emissora' => Emissora::getById($extraParameter['emissoraID']),
		
		];
		$this->parameters = $parameters;
	}
	
}