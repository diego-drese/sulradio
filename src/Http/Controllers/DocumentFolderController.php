<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\DocumentFolder;
use Yajra\DataTables\DataTables;

class DocumentFolderController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		
		if ($request->ajax()) {
			$query = DocumentFolder::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('document.folder.edit', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.document_folder.index', []);
		
	}
	
	public function create(DocumentFolder $data) {
		return $this->renderView('SulRadio::backend.document_folder.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		DocumentFolder::create($dataForm);
		Cache::tags('sulradio')->flush();
		toastr()->success('Plano Criado com sucesso', 'Sucesso');
		return redirect(route('document.folder.index'));
		
	}
	
	public function edit($id) {
		$data = DocumentFolder::getById($id);
		return $this->renderView('SulRadio::backend.document_folder.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = DocumentFolder::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		Cache::tags('sulradio')->flush();
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('document.folder.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('document.folder.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('document.folder.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('document.folder.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('document.folder.update', [1])
		];
		$this->parameters = $parameters;
	}
}