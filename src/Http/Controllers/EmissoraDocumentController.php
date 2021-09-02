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
use Oka6\SulRadio\Models\DocumentFolder;
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
	public $goal = Document::GOAL_CLIENT;
	public function index(Request $request, $emissoraID) {
		if ($request->ajax()) {
			$query = Document::select('document.*', 'document_type.name as document_type_name', 'document_folder.name as document_folder_name')
				->where('emissora_id', $emissoraID)
				->where('document.goal', $this->goal)
				->currentVersion()
				->withDocumentType()
				->withDocumentFolder();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					if($this->goal==Document::GOAL_CLIENT)
						return route('emissora.document.edit', [$row->emissora_id, $row->id]);
					if($this->goal==Document::GOAL_LEGAL)
						return route('emissora.document.legal.edit', [$row->emissora_id, $row->id]);
					if($this->goal==Document::GOAL_ENGINEERING)
						return route('emissora.document.engineering.edit', [$row->emissora_id, $row->id]);
				})->addColumn('download', function ($row) {
					return route('document.download', [$row->id]);
				})->addColumn('timeline', function ($row) {
					if($this->goal==Document::GOAL_CLIENT)
						return route('emissora.document.timeline', [$row->emissora_id, $row->id]);
					if($this->goal==Document::GOAL_LEGAL)
						return route('emissora.document.legal.timeline', [$row->emissora_id, $row->id]);
					if($this->goal==Document::GOAL_ENGINEERING)
						return route('emissora.document.engineering.timeline', [$row->emissora_id, $row->id]);
				})->setRowClass(function () {
					return 'center';
				})->rawColumns([
					'file_type'
				])->toJson(true);
		}
		
		return $this->renderView('SulRadio::backend.emissora-document.index', ['emissoraID' => $emissoraID, 'id'=>0]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-document.create', ['data' => $data, 'emissoraID' => $emissoraID, 'id'=>0, 'goal'=>$this->goal]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'document_type_id' => 'required',
			'file' => 'required|mimes:pdf,xlsx,csv,jpg,png,jpeg,html,doc,txt,xls|max:30240',
		]);
		if(!is_dir(Document::getPathUpload())){
			toastr()->error('Não foi encontrado a pasta para salvar seu arquivo, entre em contato com o adminstrador do sistema ', 'Erro');
			return $this->redirect($emissoraID);
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
		Document::createOrUpdate($dataForm, Auth::user(), $request->get('goal'), null);
		toastr()->success('Documento Criado com sucesso', 'Sucesso');
		return $this->redirect($emissoraID);
	}
	
	public function edit($emissoraID, $id) {
		$data = Document::getById($id, Auth::user());
		return $this->renderView('SulRadio::backend.emissora-document.edit', ['data' => $data, 'emissoraID' => $emissoraID, 'id'=>$id, 'goal'=>$this->goal]);
	}
	
	public function timeline($emissoraID, $id) {
		$timeline = DocumentHistoric::getTimeLineById($id, Auth::user());
		return $this->renderView('SulRadio::backend.emissora-document.timeline', ['timeline' => $timeline, 'emissoraID' => $emissoraID, 'id'=>$id]);
		
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$dataForm   = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'document_type_id' => 'required',
			'file' => 'nullable|mimes:pdf,xlsx,csv,jpg,png,jpeg,html,doc,txt,xls|max:30240',
		]);
		if(!is_dir(Document::getPathUpload())){
			toastr()->error('Não foi encontrado a pasta para salvar seu arquivo, entre em contato com o adminstrador do sistema ', 'Erro');
			return $this->redirect($emissoraID);
		}
		if($request->file()){
			$fileName   = date('YmdHis').'-'.$request->file('file')->getClientOriginalName();
			$filesize   = $request->file('file')->getSize();
			$fileType   = $request->file('file')->getMimeType();
			Storage::disk('spaces')->putFileAs("", $request->file('file'), $fileName);
			
			$dataForm['emissora_id']    = $emissoraID;
			$dataForm['status']         = 1;
			$dataForm['file_name']      = $fileName;
			$dataForm['file_type']      = $fileType;
			$dataForm['file_preview']   = '';
			$dataForm['file_size']      = $filesize;
		}
		Document::createOrUpdate($dataForm, Auth::user(), $request->get('goal'), $id);
		toastr()->success("Documento Atualizado com sucesso", 'Sucesso');
		return $this->redirect($emissoraID);
	}
	protected function redirect($emissoraID){
		if($this->goal==Document::GOAL_CLIENT)
			return redirect(route('emissora.document.index', [$emissoraID]));
		if($this->goal==Document::GOAL_LEGAL)
			return redirect(route('emissora.document.legal.index', [$emissoraID]));
		if($this->goal==Document::GOAL_ENGINEERING)
			return redirect(route('emissora.document.engineering.index', [$emissoraID]));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'goal' => $this->goal,
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.document.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.document.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.document.store', [1]),
			'hasTimeLine' => ResourceAdmin::hasResourceByRouteName('emissora.document.timeline', [1, 1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.document.update', [1, 1]),
			'documentType' => DocumentType::getWithCache($this->goal),
			'documentFolder' => DocumentFolder::getWithCache($this->goal),
			'client' => Client::getById($emissora->client_id),
			'emissora' => $emissora,
		];
		if($this->goal==Document::GOAL_CLIENT){
			$parameters['save_url'] = route('emissora.document.store', [$extraParameter['emissoraID']]);
			$parameters['add_url'] = route('emissora.document.create', [$extraParameter['emissoraID']]);
			$parameters['update_url'] = route('emissora.document.update', [$extraParameter['emissoraID'], $extraParameter['id']]);
			$parameters['datatable_url'] = route('emissora.document.index', [$extraParameter['emissoraID']]);
			$parameters['back_url'] = route('emissora.document.index', [$extraParameter['emissoraID']]);
		}else if($this->goal==Document::GOAL_LEGAL){
			$parameters['save_url'] = route('emissora.document.legal.store', [$extraParameter['emissoraID']]);
			$parameters['add_url'] = route('emissora.document.legal.create', [$extraParameter['emissoraID']]);
			$parameters['update_url'] = route('emissora.document.legal.update', [$extraParameter['emissoraID'], $extraParameter['id']]);
			$parameters['datatable_url'] = route('emissora.document.legal.index', [$extraParameter['emissoraID']]);
			$parameters['back_url'] = route('emissora.document.legal.index', [$extraParameter['emissoraID']]);
		}else if($this->goal==Document::GOAL_ENGINEERING){
			$parameters['save_url'] = route('emissora.document.engineering.store', [$extraParameter['emissoraID']]);
			$parameters['add_url'] = route('emissora.document.engineering.create', [$extraParameter['emissoraID']]);
			$parameters['update_url'] = route('emissora.document.engineering.update', [$extraParameter['emissoraID'], $extraParameter['id']]);
			$parameters['datatable_url'] = route('emissora.document.engineering.index', [$extraParameter['emissoraID']]);
			$parameters['back_url'] = route('emissora.document.engineering.index', [$extraParameter['emissoraID']]);
		}else if($this->goal==Document::GOAL_ADMIN){
			$parameters['save_url'] = route('emissora.document.admin.store', [$extraParameter['emissoraID']]);
			$parameters['add_url'] = route('emissora.document.admin.create', [$extraParameter['emissoraID']]);
			$parameters['update_url'] = route('emissora.document.admin.update', [$extraParameter['emissoraID'], $extraParameter['id']]);
			$parameters['datatable_url'] = route('emissora.document.admin.index', [$extraParameter['emissoraID']]);
			$parameters['back_url'] = route('emissora.document.admin.index', [$extraParameter['emissoraID']]);
		}
		
		$this->parameters = $parameters;
	}
	
	
}