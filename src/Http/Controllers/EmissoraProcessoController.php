<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EmissoraProcesso;
use Oka6\SulRadio\Models\EmissoraProcessoFase;
use Yajra\DataTables\DataTables;

class EmissoraProcessoController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = EmissoraProcesso::query()
				->withEmissora($emissoraID)
				->withProcessoFase();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.processo.edit', [$row->emissoraID, $row->processoID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-processo.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-processo.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'sicap' => 'required',
			'processo_faseID' => 'required',
			'assunto' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		
		EmissoraProcesso::create($dataForm);
		toastr()->success('Processo Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.processo.index', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = EmissoraProcesso::getById($id);
		return $this->renderView('SulRadio::backend.emissora-processo.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = EmissoraProcesso::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'sicap' => 'required',
			'processo_faseID' => 'required',
			'assunto' => 'required',
		]);
		
		
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.processo.index', [$emissoraID]));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.processo.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.processo.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.processo.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.processo.update', [1, 1]),
			'processoFase' => EmissoraProcessoFase::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
}