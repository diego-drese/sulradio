<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Plan;
use Yajra\DataTables\DataTables;

class PlanController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = Plan::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('plan.edit', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.plan.index', []);
		
	}
	
	public function create(Plan $data) {
		return $this->renderView('SulRadio::backend.plan.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'max_upload' => 'required',
			'max_broadcast' => 'required',
			'max_user' => 'required',
			'value' => 'required',
			'frequency' => 'required',
			'is_active' => 'required',
		]);
		Plan::create($dataForm);
		toastr()->success('Plano Criado com sucesso', 'Sucesso');
		return redirect(route('plan.index'));
		
	}
	
	public function edit($id) {
		$data = Plan::getById($id);
		return $this->renderView('SulRadio::backend.plan.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = Plan::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'max_upload' => 'required',
			'max_broadcast' => 'required',
			'max_user' => 'required',
			'value' => 'required',
			'frequency' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('plan.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('plan.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('plan.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('plan.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('plan.update', [1])
		];
		$this->parameters = $parameters;
	}
}