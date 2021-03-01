<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\TicketStatus;
use Yajra\DataTables\DataTables;

class TicketStatusController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = TicketStatus::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('ticket.status.edit', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.ticket_status.index', []);
		
	}
	
	public function create(TicketStatus $data) {
		return $this->renderView('SulRadio::backend.ticket_status.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		TicketStatus::create($dataForm);
		Cache::tags('sulradio')->flush();
		toastr()->success('Status Criado com sucesso', 'Sucesso');
		return redirect(route('ticket.status.index'));
		
	}
	
	public function edit($id) {
		$data = TicketStatus::getById($id);
		return $this->renderView('SulRadio::backend.ticket_status.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = TicketStatus::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		Cache::tags('sulradio')->flush();
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('ticket.status.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.status.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.status.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.status.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.status.update', [1])
		];
		$this->parameters = $parameters;
	}
}