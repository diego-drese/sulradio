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
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketCategory;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketDocument;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketPriority;
use Oka6\SulRadio\Models\TicketStatus;
use Yajra\DataTables\DataTables;

class TicketController extends SulradioController {
	use ValidatesRequests;
	protected $tempFolder = 'temp';
	public function index(Request $request) {
		if ($request->ajax()) {
			$user = Auth::user();
			$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
			$query = Ticket::query()
			->withSelectDataTable()
			->withStatus()
			->withParticipants($user, $hasAdmin)
			->WithPriority()
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
		return $this->renderView('SulRadio::backend.ticket.index', []);
	}
	
	public function create(Ticket $data) {
		$hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		return $this->renderView('SulRadio::backend.ticket.create', ['data' => $data, 'participants'=>[], 'hasAdmin'=>$hasAdmin]);
	}
	
	public function store(Request $request) {
		$ticketForm = $request->all();
		$owner      = Auth::user();
		$this->validate($request, [
			'subject'       => 'required',
			'priority_id'   => 'required',
			'category_id'   => 'required',
			'status_id'     => 'required',
			'start_forecast'=> 'required',
			'end_forecast'  => 'required',
			'content'       => 'required',
		]);
		$ticketForm['owner_id']         = $owner->id;
		$ticketForm['html']             = $request->get('content');
		$ticketForm['start_forecast']   = Helper::convertDateBrToMysql($ticketForm['start_forecast']);
		$ticketForm['end_forecast']     = Helper::convertDateBrToMysql($ticketForm['end_forecast']);
		
		$ticket                 = Ticket::create($ticketForm);
		TicketParticipant::insertByController($request, $ticket, $owner, TicketNotification::TYPE_NEW);
		/** Document attach */
		if(isset($ticketForm['files']) && count($ticketForm['files'])){
			foreach ($ticketForm['files'] as $names){
				try {
					$splitNames                     = explode('[--]', $names);
					$fileObj                        = new \stdClass();
					$fileObj->file_name             = $splitNames[0];
					$fileObj->file_name_original    = $splitNames[1];
					$this->uploadDocument($fileObj, $ticket, $owner);
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
		$user = Auth::user();
		$data = Ticket::getByIdOwner($id, $user);
		$emissora = Emissora::getById($data->emissora_id, $user);
		$participants = TicketParticipant::getUserByTicketId($id);
		$hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		return $this->renderView('SulRadio::backend.ticket.edit', ['data' => $data, 'emissora'=>$emissora, 'participants'=>$participants, 'hasAdmin'=>$hasAdmin]);
	}
	
	public function update(Request $request, $id) {
		$userLogged = Auth::user();
		$ticket     = Ticket::getByIdOwner($id, $userLogged);
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
		
		$ticket->fill($ticketForm);
		$ticket->save();
		
		$owner = User::getByIdStatic($ticket->owner_id);
		TicketParticipant::insertByController($request, $ticket, $owner, TicketNotification::TYPE_UPDATE);
		toastr()->success("{$ticket->subject} Atualizado com sucesso", 'Sucesso');
		return redirect(route('ticket.ticket', [$id]));
	}
	public function ticket($id) {
		$data  = Ticket::withSelectDataTable()
			->withStatus()
			->WithPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where('ticket.id', $id)
			->first();
		
		$user           = Auth::user();
		$hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$comments       = TicketComment::getAllByTicketId($id);
		$emissora       = Emissora::getById($data->emissora_id, $user);
		$documents      = TicketDocument::getAllByTicketId($id);
		$owner          = User::getByIdStatic($data->owner_id);
		$participants   = TicketParticipant::getUserByTicketId($id, true);
		
		return $this->renderView('SulRadio::backend.ticket.ticket', ['data' => $data, 'emissora'=>$emissora, 'comments'=>$comments, 'owner'=>$owner, 'participants'=>$participants, 'user'=>$user, 'hasAdmin'=>$hasAdmin, 'documents'=>$documents]);
	}
	public function comment(Request $request, $id) {
		$userLogged = Auth::user();
		$comment = $request->get('content');
		$comment = TicketComment::create([
			'content'=>$comment,
			'html'=>$comment,
			'user_id'=>$userLogged->id,
			'ticket_id'=>$id
		]);
		$ticket = Ticket::getById($id);
		$owner = User::getByIdStatic($ticket->owner_id);
		TicketParticipant::notifyParticipants($ticket, $owner, TicketNotification::TYPE_COMMENT, $comment->id);
		toastr()->success("Comentário inserido com sucesso", 'Sucesso');
		return redirect(route('ticket.ticket',[$id]));
	}
	
	public function end(Request $request, $id) {
		$userLogged = Auth::user();
		$comment = $request->get('content');
		if($comment){
			TicketComment::create([
				'content'=>$comment,
				'html'=>$comment,
				'user_id'=>$userLogged->id,
				'ticket_id'=>$id
			]);
		}
		$ticket = Ticket::getById($id);
		$ticket->completed_at = date('Y-m-d H:i:s');
		$ticket->save();
		$owner = User::getByIdStatic($ticket->owner_id);
		TicketParticipant::notifyParticipants($ticket, $owner, TicketNotification::TYPE_UPDATE);
		toastr()->success("Ticket encerrado com sucesso", 'Sucesso');
		return redirect(route('ticket.index'));
	}
	public function upload(Request $request, $id=null) {
		$files      = $request->file('file');
		$filesName  = [];
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
		}
		return response()->json(['message'=>'success', 'files'=>$filesName], 200);
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.update', [1]),
			'status' => TicketStatus::getWithCache(),
			'priority' => TicketPriority::getWithCache(),
			'category' => TicketCategory::getWithProfile($user),
			'users' => User::whereNull('client_id')->get(),
			'user'  => Auth::user()
		];
		$this->parameters = $parameters;
	}
}