<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EmissoraAtoJc;
use Oka6\SulRadio\Models\EmissoraTipoAtoJuridico;
use Yajra\DataTables\DataTables;

class EmissoraAtosComerciaisController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = EmissoraAtoJc::query()
				->withEmissora($emissoraID)
				->withTipoAtoJuridico();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.atos.comercial.edit', [$row->emissoraID, $row->ato_jcID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-ato-comercial.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-ato-comercial.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_ato_juridicoID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		EmissoraAtoJc::create($dataForm);
		toastr()->success('Ato junta comercial Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.atos.comercial.index', $emissoraID));
	}
	
	public function edit($emissoraID, $id) {
		$data = EmissoraAtoJc::getById($id);
		return $this->renderView('SulRadio::backend.emissora-ato-comercial.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = EmissoraAtoJc::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_ato_juridicoID' => 'required'
		
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.atos.comercial.index', [$emissoraID]));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.atos.comercial.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.atos.comercial.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.atos.comercial.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.atos.comercial.update', [1, 1]),
			'tipoAtoJuridico' => EmissoraTipoAtoJuridico::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
}