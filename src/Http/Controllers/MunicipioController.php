<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class MunicipioController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$query = Municipio::withUf();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('municipio.edit', [$row->municipioID]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.municipio.index', []);
	}
	
	public function create(municipio $data) {
		return $this->renderView('SulRadio::backend.municipio.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'desc_municipio' => 'required',
			'ufID' => 'required',
		]);
		Municipio::create($dataForm);
		toastr()->success('Município Criado com sucesso', 'Sucesso');
		return redirect(route('municipio.index'));
		
	}
	
	public function edit($id) {
		$data = Municipio::getById($id);
		return $this->renderView('SulRadio::backend.municipio.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = Municipio::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
            'desc_municipio' => 'required',
            'ufID' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->desc_municipio} Atualizado com sucesso", 'Sucesso');
		return redirect(route('municipio.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('municipio.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('municipio.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('municipio.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('municipio.update', [1]),
            'ufs'       => Uf::all()
		];
		$this->parameters = $parameters;
	}
}