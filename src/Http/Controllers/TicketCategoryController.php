<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\TicketCategory;
use Yajra\DataTables\DataTables;

class TicketCategoryController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = TicketCategory::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('ticket.category.edit', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.ticket_category.index', []);
		
	}
	
	public function create(TicketCategory $data) {
		return $this->renderView('SulRadio::backend.ticket_category.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		TicketCategory::create($dataForm);
		Cache::tags('sulradio')->flush();
		toastr()->success('Categoria Criada com sucesso', 'Sucesso');
		return redirect(route('ticket.category.index'));
		
	}
	
	public function edit($id) {
		$data = TicketCategory::getById($id);
		return $this->renderView('SulRadio::backend.ticket_category.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = TicketCategory::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		Cache::tags('sulradio')->flush();
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('ticket.category.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.category.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.category.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.category.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.category.update', [1])
		];
		$this->parameters = $parameters;
	}
}