<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Cities;
use Oka6\SulRadio\Models\Document;
use Oka6\SulRadio\Models\DocumentFolder;
use Oka6\SulRadio\Models\DocumentHistoric;
use Oka6\SulRadio\Models\DocumentType;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\States;
use Oka6\SulRadio\Models\SystemLog;
use Oka6\SulRadio\Models\TicketDocument;
use Yajra\DataTables\DataTables;

class PublicController extends SulradioController {
	use ValidatesRequests;
	public function searchCity(Request $request) {
		$search = $request->get('search');
		$query = Cities::where('title', 'like', $search.'%')
			->limit(10)
			->get();
		foreach ($query as &$city){
			$state = States::getByIdWithCache($city->state_id);
			$city->state_name = $state->title;
			$city->state_letter = $state->letter;
			$city->text = $city->title." ({$state->letter})";
		}
		return response()->json($query, 200);
	}
	public function searchBroadcast(Request $request) {
		$search         = $request->get('search');
		$clientID       = $request->get('client_id');
		$ignoreClient   = $request->get('ignore_client');
		$query  = Emissora::where('razao_social', 'like', '%'.$search.'%')
			->withLocalidade()
			->withServico()
			->withUf()
			->limit(25)
			->get();
		foreach ($query as &$broadcast){
			$broadcast->disabled = false;
			$broadcast->id      = $broadcast->emissoraID;
			$broadcast->name    = $broadcast->razao_social;
			$broadcast->text    = $broadcast->desc_servico.'-'.$broadcast->razao_social."({$broadcast->desc_municipio} {$broadcast->desc_uf})";
			if(!$ignoreClient && $broadcast->client_id!=null && $broadcast->client_id!=$clientID){
				$broadcast->disabled = true;
			}
		}
		return response()->json($query, 200);
	}
	
	public function getFolderAndTypeDocument($goal) {
		$folder = DocumentFolder::getWithCache($goal);
		$type   = DocumentType::getWithCache($goal);
		return response()->json(['folder'=>$folder, 'type'=>$type], 200);
	}
	
	public function downloadDocument($id){
		$user = Auth::user();
		$document = Document::getById($id, $user);
		if(!$document){
			return redirect(route('admin.page404get'));
		}
		
		$urlTemp = Storage::disk('spaces')->temporaryUrl($document->file_name, now()->addMinutes(5));
		DocumentHistoric::create([
			'user_id'=> $user->id,
			'document_id'=> $document->id,
			'action'=> DocumentHistoric::ACTION_DOWNLOADED,
		]);
		return redirect($urlTemp);
	}
	public function downloadDocumentTicket($id){
		$user = Auth::user();
		$document = TicketDocument::getById($id);
		if(!$document){
			return redirect(route('admin.page404get'));
		}
		
		$urlTemp = Storage::disk('spaces')->temporaryUrl('tickets/'.$document->file_name, now()->addMinutes(5));
		return redirect($urlTemp);
	}
	public function removeDocumentTicket(Request $request, $id=null) {
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$update     = TicketDocument::removeById($id, Auth::user(), $hasAdmin);
		if($update){
			$userLogged = Auth::user();
			$contentLog = 'Usuário '.$userLogged->name. ' removeu um arquivo ao ticket '. $update->ticket_id. ' arquivo['.$update->file_name.']';
			SystemLog::insertLogTicket(SystemLog::TYPE_DELETE_UPLOAD, $contentLog, $id, $userLogged->id);
			return response()->json(['message'=>'success'], 200);
		}else{
			return response()->json(['message'=>'Erro ao remover o arquivo, somente admin e o propio usuário que subio o arquivo podem remover. '], 500);
		}
	}

	public function markToReadNotificationsTicket() {
		$user = Auth::user();
		SystemLog::updateToRead($user->id, SystemLog::ZONE_TICKET);
		return response()->json(['message'=>'success'], 200);
	}

	public function notificationsTicket(Request $request) {
		$user = Auth::user();
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');

		if ($request->ajax()) {
			if($hasAdmin){
				$query      = SystemLog::getNotifications($request);
			}else{
				$query      = SystemLog::getNotificationsTicket($user->id);
			}

			return DataTables::of($query)
				->addColumn('status_name', function ($row) {
					return SystemLog::getStatusText($row->status);
				})->addColumn('user_name', function ($row) {
					return User::getByIdStatic($row->user_id)->name;
				})->addColumn('type_name', function ($row) {
					return SystemLog::getTypeText($row->type);
				})->addColumn('zone_name', function ($row) {
					return SystemLog::getZoneText($row->zone);
				})->addColumn('status_class', function ($row) {
					return $row->status==SystemLog::STATUS_NEW ? 'text-info' :'text-success';
				})->addColumn('created', function ($row) {
					return $row->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i');
				})->addColumn('updated', function ($row) {
					return $row->updated_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i');
				})->toJson(true);
		}
		SystemLog::updateToRead($user->id, SystemLog::ZONE_TICKET);

		$users      = null;
		$status     = SystemLog::STATUS_TEXT;
		$types      = SystemLog::TYPE_TEXT;
		$zones      = SystemLog::ZONE_TEXT;

		if($hasAdmin){
			$users= User::all();
		}

		return $this->renderView('SulRadio::backend.system_log.index',
			['hasAdmin'=>$hasAdmin, 'status'=>$status, 'types'=>$types,'zones'=>$zones, 'users'=>$users, 'user'=>$user]);
	}
	
}