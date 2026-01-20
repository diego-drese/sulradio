<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as Download;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\Resource;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Cities;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Document;
use Oka6\SulRadio\Models\DocumentFolder;
use Oka6\SulRadio\Models\DocumentHistoric;
use Oka6\SulRadio\Models\DocumentType;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EstacaoRd;
use Oka6\SulRadio\Models\States;
use Oka6\SulRadio\Models\SystemLog;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketDocument;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketNotificationClient;
use Oka6\SulRadio\Models\TicketNotificationClientUser;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketUrlTracker;
use Oka6\SulRadio\Models\UserSulRadio;
use Yajra\DataTables\DataTables;

class PublicController extends SulradioController {
	use ValidatesRequests;
	protected $tempFolder = 'temp';
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

	public function searchClient(Request $request) {
		$search = $request->get('search');
		$query = Client::where('name', 'like', '%'.$search.'%')
			->orWhere('company_name', 'like', '%'.$search.'%')
			->limit(10)
			->get();
		foreach ($query as &$client){
			$client->text = $client->name." ({$client->company_name})";
		}
		return response()->json($query, 200);
	}
	public function searchUserTicket(Request $request) {
		$search = $request->get('search');
		$userId = (int)$request->get('user_id');
		$query = UserSulRadio::whereNull('client_id')
			->where(function ($query) use($search){
				$query->where('name', 'like', '%'.$search.'%')
					->orWhere('last_name', 'like', '%'.$search.'%')
					->orWhere('email', 'like', '%'.$search.'%');
		})
		->when($userId, function ($query) use($userId) {
			return $query->where('id', '!=', $userId);
		})
		->limit(10)
		->get();
		foreach ($query as &$user){
			$user->text = $user->name." ({$user->lastname})";
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
	
	public function getIdFromFistel($fistel) {
		$emissora = EstacaoRd::getByFistel($fistel);
		return response()->json(['id'=>isset($emissora->id)? $emissora->id :null, 'state'=>isset($emissora->state)? $emissora->state :null], 200);
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
		//$urlTemp = Storage::disk('spaces')->temporaryUrl('tickets/'.$document->file_name, now()->addMinutes(5));
		$headers = [
			'Content-Type'        => "Content-Type: {$document->file_type}",
			'Content-Disposition' => 'attachment; filename="'. $document->file_name_original .'"',
		];
		return Download::make(Storage::disk('spaces')->get('tickets/'.$document->file_name), Response::HTTP_OK, $headers);
		//return redirect($urlTemp);
	}

	public function deleteDocumentTicket(Request $request, $id=null) {
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $documentsId = $request->get('documents');
        $userLogged = Auth::user();
        foreach ($documentsId as  $docId){
            $update     = TicketDocument::removeById($docId, ($hasAdmin) ?? false);
            if($update) {
                $contentLog = 'Usuário ' . $userLogged->name . ' deletou permanentemente um arquivo do ticket ' . $update->ticket_id . ' arquivo[' . $update->file_name . ']';
                SystemLog::insertLogTicket(SystemLog::TYPE_DELETE_UPLOAD, $contentLog, $id, $userLogged->id);
            }
        }
        return response()->json(['message'=>'success'], 200);
	}

    public function ticketMove(Request $request) {
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $idCurrent = $request->get('idCurrent');
        $idToMove = $request->get('idToMove');
        if($hasAdmin){
            $ticket = Ticket::getById($idToMove);
            if(!$ticket){
                return response()->json(['message'=>'Ticket não encontrado'], 500);
            }

            $ticketComment = TicketComment::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);
            $ticketDocument = TicketDocument::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);
            $ticketNotification = TicketNotification::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);
            $ticketNotificationClient = TicketNotificationClient::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);
            $ticketUrlTracker = TicketUrlTracker::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);
            $systemLog = SystemLog::where('ticket_id', $idCurrent)->update(['ticket_id'=> $idToMove]);

            /** Delete Ticket */
            Ticket::where('id', $idCurrent)->delete();

            $userLogged = Auth::user();
            $contentLog = 'Usuário ' . $userLogged->name . ' moveu os dados do ticket id['.$idCurrent.'] para['.$idToMove.']';
            SystemLog::insertLogTicket(SystemLog::TYPE_MOVE_TICKET, $contentLog, $idToMove, $userLogged->id);

            /** Add comment with information */
            $comment = TicketComment::create([
                'html'=>$contentLog,
                'user_id'=>$userLogged->id,
                'ticket_id'=>$idToMove
            ]);
            /** Notifica todos os participantes */
            $ticket = Ticket::getById($idToMove);
            TicketParticipant::notifyParticipants($ticket, $userLogged,TicketNotification::TYPE_COMMENT, $comment->id);
        }

        return response()->json(['message'=>'success'], 200);
	}
    public function archivedDocumentTicket(Request $request) {
        $documentsId = $request->get('documents');
        foreach ($documentsId as  $docId){
            $update     = TicketDocument::archivedById($docId);
            if($update){
                $userLogged = Auth::user();
                $contentLog = 'Usuário '.$userLogged->name. ' arquivou um arquivo do ticket '. $update->ticket_id. ' arquivo['.$update->file_name.']';
                SystemLog::insertLogTicket(SystemLog::TYPE_DELETE_UPLOAD, $contentLog, $docId, $userLogged->id);
            }
        }
        return response()->json(['message'=>'success'], 200);
	}

	public function markToReadNotificationsTicket() {
		$user = Auth::user();
		SystemLog::updateToRead($user->id, SystemLog::ZONE_TICKET);
		return response()->json(['message'=>'success'], 200);
	}

	public function loadTicketNotificationClient($id) {
		$ticketNotificationClient = TicketNotificationClient::getById($id);
		$users = TicketNotificationClientUser::getByTicketNotificationClientId($id, false, false);
		$attach=[];
		for ($i=1; $i<11; $i++){
			$send_file='send_file_'.$i;
			if(!empty($ticketNotificationClient->$send_file)){
				$attach[] = TicketDocument::getById($ticketNotificationClient->$send_file);
			}
		}
		return response()->json(['message'=>'success', 'ticketNotificationClient'=>$ticketNotificationClient, 'users'=>$users, 'attach'=>$attach], 200);
	}

    public function ticketClientAnswer(Request $request, $id) {
        /** 🔎 Localiza o vínculo pelo identify */
        $ticketNotificationClientUser = TicketNotificationClientUser::getByIdentify($id);
        if (!$ticketNotificationClientUser) {
            toastr()->error('Link inválido ou expirado', 'Erro');
            return redirect('/');
        }
        /** 🔐 Busca o usuário correto */
        $user = UserSulRadio::getByIdStatic($ticketNotificationClientUser->user_id);
        if (!$user) {
            toastr()->error('Usuário não encontrado', 'Erro');
            return redirect('/');
        }
        /** 🔥 LOGIN AUTOMÁTICO */
        Auth::guard('web')->login($user, true);
        /** 🧠 Usuário autenticado */
        $user = Auth::user();
        /** 🔎 Busca notificação e ticket */
        $ticketNotificationClient = TicketNotificationClient::getById(
            $ticketNotificationClientUser->ticket_notification_client_id
        );
        if (!$ticketNotificationClient) {
            toastr()->error('Ticket não encontrado', 'Erro');
            return redirect('/');
        }
        /** 🛑 Evita responder mais de uma vez */
        if (
            $ticketNotificationClientUser->status ==
            TicketNotificationClientUser::getStatusText(TicketNotificationClientUser::STATUS_ANSWERED)
        ) {
            toastr()->info('Este link já foi utilizado', 'Info');
            return redirect('/');
        }

        /** 📋 Recurso padrão do usuário */
        $resource = Resource::where('id', (int)$user->resource_default_id)->first();

        /** 📝 POST — envio da resposta */
        if ($request->isMethod('post')) {

            if (!$request->get('content')) {
                toastr()->info('Preencha o texto para enviar', 'Info');
                return redirect()->back();
            }

            /** Atualiza status da notificação */
            $ticketNotificationClient->status = TicketNotificationClient::STATUS_ANSWERED;
            $ticketNotificationClient->total_answered++;
            $ticketNotificationClient->save();

            /** Salva resposta do usuário */
            $ticketNotificationClientUser->status = TicketNotificationClientUser::STATUS_ANSWERED;
            $ticketNotificationClientUser->answer = $request->get('content');
            $ticketNotificationClientUser->answer_date_at = now();

            /** 📎 Upload de anexos */
            for ($i = 0; $i < 11; $i++) {
                $answerFile = "answer_file_{$i}";
                $file = $request->file($answerFile);

                if ($file) {
                    $fileName = now()->format('YmdHis') . '-' . $file->getClientOriginalName();
                    $file->storeAs($this->tempFolder, $fileName);

                    $path     = $this->tempFolder . '/' . $fileName;
                    $filesize = Storage::size($path);
                    $fileType = Storage::mimeType($path);

                    Storage::disk('spaces')->putFileAs(
                        'tickets',
                        storage_path('app/' . $path),
                        $fileName
                    );

                    $document = TicketDocument::create([
                        'ticket_id'           => $ticketNotificationClient->ticket_id,
                        'user_id'             => $user->id,
                        'file_name'           => $fileName,
                        'file_name_original'  => $file->getClientOriginalName(),
                        'file_type'           => $fileType,
                        'file_preview'        => 'client',
                        'file_size'           => $filesize,
                        'removed'             => 0,
                    ]);

                    Storage::delete($path);

                    $ticketNotificationClientUser->$answerFile = $document->id;
                }
            }

            $ticketNotificationClientUser->save();

            /** 🔔 Notifica participantes */
            $ticket = Ticket::getById($ticketNotificationClient->ticket_id);

            TicketParticipant::notifyParticipants(
                $ticket,
                $user,
                TicketNotification::TYPE_COMMENT_CLIENT,
                $ticketNotificationClientUser->id
            );

            /** 📜 Log */
            Log::info('Resposta enviada via link automático', [
                'user_id'   => $user->id,
                'ticket_id' => $ticket->id,
                'identify'  => $id,
                'ip'        => $request->ip(),
            ]);

            toastr()->success('Comentário enviado com sucesso', 'Sucesso');
            return redirect(route($resource->route_name));
        }

        /** 📎 GET — anexos enviados */
        $attach = [];
        for ($i = 1; $i < 11; $i++) {
            $sendFile = "send_file_{$i}";
            if ($ticketNotificationClient->$sendFile) {
                $attach[] = TicketDocument::getById($ticketNotificationClient->$sendFile);
            }
        }

        /** 🖥 Renderiza a view */
        return $this->renderView(
            'SulRadio::backend.ticket.answer-client',
            [
                'ticketNotificationClient'     => $ticketNotificationClient,
                'ticketNotificationClientUser' => $ticketNotificationClientUser,
                'attach'                       => $attach,
            ]
        );
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
				})->addColumn('ticket_url', function ($row) {
					return $row->ticket_id ? route('ticket.ticket', [$row->ticket_id]) : null;
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