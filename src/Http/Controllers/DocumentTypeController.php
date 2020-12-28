<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\DocumentType;
use Yajra\DataTables\DataTables;

class DocumentTypeController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = DocumentType::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('document.type.edit', [$row->_id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.document_type.index', []);
		
	}
	
	public function create(DocumentType $data) {
		return $this->renderView('SulRadio::backend.document_type.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		DocumentType::create($dataForm);
		toastr()->success('Plano Criado com sucesso', 'Sucesso');
		return redirect(route('document.type.index'));
		
	}
	
	public function edit($id) {
		$data = DocumentType::getById($id);
		return $this->renderView('SulRadio::backend.document_type.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = DocumentType::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('document.type.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('document.type.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('document.type.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('document.type.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('document.type.update', [1])
		];
		$this->parameters = $parameters;
	}
}