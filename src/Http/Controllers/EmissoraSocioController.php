<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EmissoraEndereco;
use Oka6\SulRadio\Models\EmissoraProcesso;
use Oka6\SulRadio\Models\EmissoraProcessoFase;
use Oka6\SulRadio\Models\EmissoraSocioCargo;
use Oka6\SulRadio\Models\EmissoraSocioCategoria;
use Oka6\SulRadio\Models\EmissoraSocioDado;
use Oka6\SulRadio\Models\EmissoraTipoEndereco;
use Oka6\SulRadio\Models\EmissoraTipoIngresso;
use Oka6\SulRadio\Models\EstadoCivil;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraSocioController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = EmissoraSocioDado::query()
				->withEmissora($emissoraID)
				->withCategoria();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.socio.edit', [$row->emissoraID, $row->socioID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-socio.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-socio.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'categoria_socioID' => 'required',
			'nome' => 'required',
			'ufID' => 'required',
			'municipioID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		EmissoraSocioDado::create($dataForm);
		toastr()->success('Sócio Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.edit', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = EmissoraSocioDado::getById($id);
		$data->ufID = null;
		$ufID = Municipio::getById($data->municipioID);
		if ($ufID) {
			$data->ufID = $ufID->ufID;
		}
		return $this->renderView('SulRadio::backend.emissora-socio.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = EmissoraSocioDado::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_socioID' => 'required',
			'logradouro' => 'required',
			'ufID' => 'required',
			'municipioID' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.edit', $emissoraID));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.socio.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.socio.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.socio.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.socio.update', [1, 1]),
			'categoria' => EmissoraSocioCategoria::getWithCache(),
			'cargo' => EmissoraSocioCargo::getWithCache(),
			'estadoCivil' => EstadoCivil::getWithCache(),
			'tipoIngresso' => EmissoraTipoIngresso::getWithCache(),
			'uf' => Uf::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
	
}