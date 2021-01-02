<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Document;
use Oka6\SulRadio\Models\DocumentHistoric;
use Oka6\SulRadio\Models\DocumentType;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EmissoraEndereco;
use Oka6\SulRadio\Models\EmissoraProcesso;
use Oka6\SulRadio\Models\EmissoraProcessoFase;
use Oka6\SulRadio\Models\EmissoraTipoEndereco;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraDocumentController extends SulradioController {
	use ValidatesRequests;
	public function index(Request $request, $emissoraID) {
		if ($request->ajax()) {
			$query = Document::select('document.*', 'document_type.name as document_type_name')
				->where('emissora_id', $emissoraID)
				->currentVersion()
				->withDocumentType();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.document.edit', [$row->emissora_id, $row->id]);
				})->addColumn('download', function ($row) {
					return route('document.download', [$row->id]);
				})->addColumn('timeline', function ($row) {
					return route('emissora.document.timeline', [$row->emissora_id, $row->id]);
				})->setRowClass(function () {
					return 'center';
				})->rawColumns([
					'file_type'
				])->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-document.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-document.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'description' => 'required',
			'document_type_id' => 'required',
			'file' => 'required|mimes:pdf,xlsx,csv,jpg,png,jpeg,html,doc,txt,xls|max:10240',
		]);
		if(!is_dir(Document::getPathUpload())){
			toastr()->error('Não foi encontrado a pasta para salvar seu arquivo, entre em contato com o adminstrador do sistema ', 'Erro');
			return redirect(route('emissora.document.index', $emissoraID));
		}
		$fileName   = date('YmdHis').'-'.$request->file('file')->getClientOriginalName();
		$filesize   = $request->file('file')->getSize();
		$fileType   = $request->file('file')->getMimeType();
		Storage::disk('spaces')->putFileAs("", $request->file('file'), $fileName);
		$dataForm['emissora_id']    = $emissoraID;
		$dataForm['file_name']      = $fileName;
		$dataForm['file_type']      = $fileType;
		$dataForm['file_preview']   = '';
		$dataForm['file_size']      = $filesize;
		$dataForm['status']         = 1;
		Document::createOrUpdate($dataForm, Auth::user(), null);
		
		toastr()->success('Documento Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.document.index', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = Document::getById($id, Auth::user());
		return $this->renderView('SulRadio::backend.emissora-document.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function timeline($emissoraID, $id) {
		$timeline = DocumentHistoric::getTimeLineById($id, Auth::user());
		return $this->renderView('SulRadio::backend.emissora-document.timeline', ['timeline' => $timeline, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$dataForm   = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'description' => 'required',
			'document_type_id' => 'required',
			'file' => 'nullable|mimes:pdf,xlsx,csv,jpg,png,jpeg,html,doc,txt,xls|max:10240',
		]);
		if(!is_dir(Document::getPathUpload())){
			toastr()->error('Não foi encontrado a pasta para salvar seu arquivo, entre em contato com o adminstrador do sistema ', 'Erro');
			return redirect(route('emissora.document.index', $emissoraID));
		}
		if($request->file()){
			$fileName   = date('YmdHis').'-'.$request->file('file')->getClientOriginalName();
			$filesize   = $request->file('file')->getSize();
			$fileType   = $request->file('file')->getMimeType();
			Storage::disk('spaces')->putFileAs("", $request->file('file'), $fileName);
			
			$dataForm['emissora_id']    = $emissoraID;
			$dataForm['file_name']      = $fileName;
			$dataForm['file_type']      = $fileType;
			$dataForm['file_preview']   = '';
			$dataForm['file_size']      = $filesize;
			$dataForm['status']         = 1;
		}
		Document::createOrUpdate($dataForm, Auth::user(), $id);
		toastr()->success("Documento Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.document.index', [$emissoraID]));
	}
	
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.document.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.document.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.document.store', [1]),
			'hasTimeLine' => ResourceAdmin::hasResourceByRouteName('emissora.document.timeline', [1, 1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.document.update', [1, 1]),
			'documentType' => DocumentType::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
	
	
}