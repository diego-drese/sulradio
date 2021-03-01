<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\TicketPriority;
use Yajra\DataTables\DataTables;

class TicketPriorityController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = TicketPriority::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('ticket.priority.edit', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.ticket_priority.index', []);
		
	}
	
	public function create(TicketPriority $data) {
		return $this->renderView('SulRadio::backend.ticket_priority.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		TicketPriority::create($dataForm);
		Cache::tags('sulradio')->flush();
		toastr()->success('Prioridade Criada com sucesso', 'Sucesso');
		return redirect(route('ticket.priority.index'));
		
	}
	
	public function edit($id) {
		$data = TicketPriority::getById($id);
		return $this->renderView('SulRadio::backend.ticket_priority.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = TicketPriority::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		Cache::tags('sulradio')->flush();
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('ticket.priority.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.priority.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.priority.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.priority.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.priority.update', [1])
		];
		$this->parameters = $parameters;
	}
}