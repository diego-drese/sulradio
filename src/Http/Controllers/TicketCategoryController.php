<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\Profile;
use Oka6\SulRadio\Models\TicketCategory;
use Oka6\SulRadio\Models\TicketCategoryProfile;
use Yajra\DataTables\DataTables;

class TicketCategoryController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = TicketCategory::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('ticket.category.edit', [$row->id]);
				})->addColumn('profiles', function ($row) {
					$profilesCategory   = TicketCategoryProfile::getProfilesByTicketCategoryId($row->id);
					return Profile::getByArrayId($profilesCategory)->pluck('name')->toArray();
				})->toJson(true);
		}
		$profiles = Profile::all();
		return $this->renderView('SulRadio::backend.ticket_category.index', ['profiles' => $profiles,]);
		
	}
	
	public function create(TicketCategory $data) {
		$profilesCategory = [];
		$profiles = Profile::all();
		return $this->renderView('SulRadio::backend.ticket_category.create', ['data' => $data, 'profiles' => $profiles, 'profilesCategory'=>$profilesCategory]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$ticketCategory = TicketCategory::create($dataForm);
		TicketCategoryProfile::createFromForm($request, $ticketCategory, Auth::user());
		Cache::tags('sulradio')->flush();
		toastr()->success('Categoria Criada com sucesso', 'Sucesso');
		return redirect(route('ticket.category.index'));
	}
	
	public function edit($id) {
		$data = TicketCategory::getById($id);
		$profilesCategory = TicketCategoryProfile::getProfilesByTicketCategoryId($id);
		$profiles = Profile::all();
		return $this->renderView('SulRadio::backend.ticket_category.edit',['data' => $data, 'profiles' => $profiles, 'profilesCategory'=>$profilesCategory]);
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
		TicketCategoryProfile::createFromForm($request, $data, Auth::user());
		
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