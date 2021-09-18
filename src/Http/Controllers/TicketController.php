<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\SystemLog;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketCategory;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketDocument;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketPriority;
use Oka6\SulRadio\Models\TicketStatus;
use Oka6\SulRadio\Models\TicketUrlTracker;
use Yajra\DataTables\DataTables;

class TicketController extends SulradioController {
	use ValidatesRequests;
	protected $tempFolder = 'temp';
	public function index(Request $request) {
		$user = Auth::user();
		if ($request->ajax()) {
			$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
			$query = Ticket::query()
			->withSelectDataTable()
			->withParticipants($user, $hasAdmin)
			->withStatus()
			->withPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->groupBy('ticket.id');
			/** Filters */
			if($request->get('active')=='1'){
				$query->whereNull('completed_at');
			}else{
				$query->whereNotNull('completed_at');
			}
			$contentLog = 'Visualização dos tickets';
			SystemLog::insertLogTicket(SystemLog::TYPE_VIEW, $contentLog, null, $user->id);

			return DataTables::of($query)
				->addColumn('ticket_url', function ($row) {
					return route('ticket.ticket', [$row->id]);
				})->addColumn('emissora_nome', function ($row) {
					return $row->desc_servico.'-'.$row->emissora."({$row->desc_municipio} {$row->desc_uf})";
				})->addColumn('user_name', function ($row) use($user){
					$userData = $user->getById($row->owner_id);
					return $userData->name;
				})->addColumn('participants', function ($row)  use($user){
					$participants = TicketParticipant::getUserNameByTicketId($row->id);
					return $participants;
				})->toJson(true);
		}

		return $this->renderView('SulRadio::backend.ticket.index');
	}
	
	public function create(Ticket $data) {
		$hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		return $this->renderView('SulRadio::backend.ticket.create', ['data' => $data, 'participants'=>[], 'hasAdmin'=>$hasAdmin]);
	}
	
	public function store(Request $request) {
		$ticketForm = $request->all();
		$userLogged      = Auth::user();
		$this->validate($request, [
			'subject'       => 'required',
			'priority_id'   => 'required',
			'category_id'   => 'required',
			'status_id'     => 'required',
			'start_forecast'=> 'required',
			'end_forecast'  => 'required',
			'content'       => 'required',
		]);
		$ticketForm['owner_id']         = $userLogged->id;
		$ticketForm['html']             = $request->get('content');
		$ticketForm['start_forecast']   = Helper::convertDateBrToMysql($ticketForm['start_forecast']);
		$ticketForm['end_forecast']     = Helper::convertDateBrToMysql($ticketForm['end_forecast']);
		
		$ticket = Ticket::create($ticketForm);
		if(TicketStatus::statusFinished($request->get('status_id'))){
			$ticketForm['completed_at'] = date('Y-m-d H:i:s');
		}
		TicketParticipant::insertByController($request, $ticket, $userLogged, TicketNotification::TYPE_NEW);
		/** Document attach */
		if(isset($ticketForm['files']) && count($ticketForm['files'])){
			foreach ($ticketForm['files'] as $names){
				try {
					$splitNames                     = explode('[--]', $names);
					$fileObj                        = new \stdClass();
					$fileObj->file_name             = $splitNames[0];
					$fileObj->file_name_original    = $splitNames[1];
					$this->uploadDocument($fileObj, $ticket, $userLogged);
				}catch (\Exception $e){
					toastr()->error('Ocorreu um erro em anexar seus arquivos.', 'Erro');
				}
			}
		}
		toastr()->success('Ticket criado com sucesso', 'Sucesso');
		return redirect(route('ticket.index'));
		
	}
	protected function uploadDocument($fileObj, $ticket, $user){
		$fileName   = $fileObj->file_name;
		$path       = $this->tempFolder.'/'.$fileObj->file_name;
		$filesize   = Storage::size($path);
		$fileType   = Storage::mimeType($path);
		Storage::disk('spaces')->putFileAs("tickets", storage_path('app/'.$path), $fileName);
		$documentSave = [
			'ticket_id'=>$ticket->id,
			'user_id'=>$user->id,
			'file_name'=>$fileName,
			'file_name_original'=>$fileObj->file_name_original,
			'file_type'=>$fileType,
			'file_preview'=>'',
			'file_size'=>$filesize,
			'removed'=>0,
		];
		TicketDocument::create($documentSave);
		Storage::delete($path);
	}
	public function edit($id) {
		$user       = Auth::user();
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$data       = Ticket::withSelectDataTable()
			->withParticipants($user, $hasAdmin)
			->withStatus()
			->WithPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where('ticket.id', $id)
			->first();
		if(!$data){
			return redirect(route('admin.page403get'));
		}
		$emissora = Emissora::getById($data->emissora_id, $user);
		$participants = TicketParticipant::getUserByTicketId($id);

		return $this->renderView('SulRadio::backend.ticket.edit', ['data' => $data, 'emissora'=>$emissora, 'participants'=>$participants, 'hasAdmin'=>$hasAdmin]);
	}
	
	public function update(Request $request, $id) {
		$userLogged = Auth::user();
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$ticket     = Ticket::select('ticket.*')
			->withParticipants($userLogged, $hasAdmin)
			->where('ticket.id', $id)
			->first();
		if(!$ticket){
			return redirect(route('admin.page403get'));
		}
		$ticketForm = $request->all();
		$this->validate($request, [
			'subject'       => 'required',
			'priority_id'   => 'required',
			'category_id'   => 'required',
			'status_id'     => 'required',
			'start_forecast'=> 'required',
			'end_forecast'  => 'required',
			'content'       => 'required',
		]);
		$ticketForm['html']             = $request->get('content');
		$ticketForm['start_forecast']   = Helper::convertDateBrToMysql($ticketForm['start_forecast']);
		$ticketForm['end_forecast']     = Helper::convertDateBrToMysql($ticketForm['end_forecast']);
		$ticketForm['completed_at']     = null;
		if($hasAdmin){
			$ticketForm['show_client']      = isset($ticketForm['show_client']) ?? 0;
		}

		if(TicketStatus::statusFinished($request->get('status_id'))){
			$ticketForm['completed_at'] = date('Y-m-d H:i:s');
		}
		$ticket->fill($ticketForm);
		$ticket->save();
		
		TicketParticipant::insertByController($request, $ticket, $userLogged, TicketNotification::TYPE_UPDATE);
		toastr()->success("{$ticket->subject} Atualizado com sucesso", 'Sucesso');
		return redirect(route('ticket.ticket', [$id]));
	}
	public function ticket($id) {
		$user           = Auth::user();
		$hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$data  = Ticket::withSelectDataTable()
			->withParticipants($user, $hasAdmin)
			->withStatus()
			->WithPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where('ticket.id', $id)
			->first();
		if(!$data){
			return redirect(route('admin.page403get'));
		}
		$comments       = TicketComment::getAllByTicketId($id);
		$emissora       = Emissora::getById($data->emissora_id, $user);
		$documents      = TicketDocument::getAllByTicketId($id);
		$trackerUrl     = TicketUrlTracker::active()->where('ticket_id', $id)->get();
		$owner          = User::getByIdStatic($data->owner_id);
		$participants   = TicketParticipant::getUserByTicketId($id, true);
		$contentLog     = 'Usuário '.$user->name. ' visualizou o ticket '. $id;
		SystemLog::insertLogTicket(SystemLog::TYPE_VIEW, $contentLog, $id, $user->id);
		return $this->renderView('SulRadio::backend.ticket.ticket', ['data' => $data, 'emissora'=>$emissora, 'comments'=>$comments, 'owner'=>$owner, 'participants'=>$participants, 'user'=>$user, 'hasAdmin'=>$hasAdmin, 'documents'=>$documents, 'trackerUrl'=>$trackerUrl]);
	}
	public function comment(Request $request, $id) {
		$userLogged = Auth::user();
		$comment = $request->get('content');
		$comment = TicketComment::create([
			'html'=>$comment,
			'user_id'=>$userLogged->id,
			'ticket_id'=>$id
		]);
		$ticket = Ticket::getById($id);
		TicketParticipant::notifyParticipants($ticket, $userLogged, TicketNotification::TYPE_COMMENT, $comment->id);
		toastr()->success("Comentário inserido com sucesso", 'Sucesso');
		return redirect(route('ticket.ticket',[$id]));
	}
	
	public function end(Request $request, $id) {
		$userLogged = Auth::user();
		$comment = $request->get('content');
		if($comment){
			TicketComment::create([
				'html'=>$comment,
				'user_id'=>$userLogged->id,
				'ticket_id'=>$id
			]);
		}
		
		$ticket = Ticket::getById($id);
		$status = TicketStatus::statusFinished();
		$ticket->completed_at = date('Y-m-d H:i:s');
		if($status){
			$ticket->status_id = $status->id;
		}
		$ticket->save();
		TicketParticipant::notifyParticipants($ticket, $userLogged, TicketNotification::TYPE_UPDATE);
		toastr()->success("Ticket encerrado com sucesso", 'Sucesso');
		return redirect(route('ticket.index'));
	}
	public function upload(Request $request, $id=null) {
		$files      = $request->file('file');
		$filesName  = [];
		$userLogged = Auth::user();
		foreach ($files as $file){
			$fineName = date('YmdHis').'-'.$file->getClientOriginalName();
			$file->storeAs(
				$this->tempFolder, $fineName
			);
			if($id){
				$ticket = Ticket::getById($id);
				$fileObj = new \stdClass();
				$fileObj->file_name = $fineName;
				$fileObj->file_name_original = $file->getClientOriginalName();
				$this->uploadDocument($fileObj, $ticket, Auth::user());
			}
			$filesName[]= ['file_name'=>$fineName, 'file_name_original'=>$file->getClientOriginalName()];
			$contentLog = 'Usuário '.$userLogged->name. ' anexou um arquivo ao ticket '. $ticket->id. ' arquivo['.$fineName.']';
			SystemLog::insertLogTicket(SystemLog::TYPE_SAVE_UPLOAD, $contentLog, $id, $userLogged->id);
		}
		return response()->json(['message'=>'success', 'files'=>$filesName], 200);
	}

	public function saveTrackerUrl(Request $request, $id=null) {
		$name=$request->name;
		$url=$request->url;
		if(!$name || empty($name)){
			return response()->json(['message'=>'Preencha o nome'], 500);
		}
		if(!$url || empty($url) || !filter_var($url, FILTER_VALIDATE_URL)){
			return response()->json(['message'=>'Verifique a url digitada, a url deve conter http ou https.'], 500);
		}
		$parse = parse_url($url);
		$domain = $parse['host'];
		$domains = ['sei.anatel.gov.br', 'sei.mctic.gov.br'];
		if(!in_array($domain, $domains)){
			return response()->json(['message'=>'Somente os domínios [<b>'.implode(' , ', $domains).'</b>] são permitidos'], 500);
		}

		TicketUrlTracker::create($request->all());
		$userLogged = Auth::user();
		$contentLog = 'Usuário '.$userLogged->name. ' anexou uma url de rastreamento ao ticket '. $id. ' url['.$request->url.']';
		SystemLog::insertLogTicket(SystemLog::TYPE_SAVE_TRACKER_URL, $contentLog, $id, $userLogged->id);
		return response()->json(['message'=>'success'], 200);
	}

	public function deleteTrackerUrl(Request $request, $id=null) {
		$ticketUrlTracker = TicketUrlTracker::getById($id);
		$ticketUrlTracker->status = 0;
		$ticketUrlTracker->save();
		$userLogged = Auth::user();
		$contentLog = 'Usuário '.$userLogged->name. ' removeu uma url de rastreamento do ticket '. $id. ' url['.$ticketUrlTracker->url.'] id['.$ticketUrlTracker->id.']';
		SystemLog::insertLogTicket(SystemLog::TYPE_DELETE_TRACKER_URL, $contentLog, $ticketUrlTracker->ticket_id, $userLogged->id);
		return response()->json(['message'=>'success'], 200);
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.update', [1]),
			'status' => TicketStatus::getWithCache(),
			'statusFinished' => TicketStatus::statusFinished(),
			'priority' => TicketPriority::getWithCache(),
			'category' => TicketCategory::getWithProfile($user),
			'users' => User::whereNull('client_id')->get(),
			'user'  => Auth::user()
		];
		$this->parameters = $parameters;
	}
}