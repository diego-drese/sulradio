<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\SystemLog;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketCategory;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketDocument;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketNotificationClient;
use Oka6\SulRadio\Models\TicketNotificationClientUser;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketPriority;
use Oka6\SulRadio\Models\TicketStatus;
use Oka6\SulRadio\Models\TicketUrlTracker;
use Oka6\SulRadio\Models\UserSulRadio;
use Yajra\DataTables\DataTables;

class TicketManagementController extends SulradioController {
	use ValidatesRequests;
    public function index(Request $request){
        $user = Auth::user();
        if ($request->ajax()) {
            $hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
            $contentLog = 'Visualização gerência dos tickets';
            SystemLog::insertLogTicket(SystemLog::TYPE_VIEW, $contentLog, null, $user->id);
            return DataTables::of($this->makeQuery($request, $user, $hasAdmin))
                ->addColumn('ticket_url', function ($row) {
                    return route('ticket.ticket', [$row->id]);
                })->addColumn('emissora_nome', function ($row) {
                    return $row->desc_servico.'-'.$row->emissora."({$row->desc_municipio} {$row->desc_uf})";
                })->addColumn('user_name', function ($row) use($user){
                    $userData = $user->getById($row->owner_id);
                    return $userData ? $userData->name : '---';
                })->addColumn('participants', function ($row)  use($user){
                    $participants = TicketParticipant::getUserNameByTicketId($row->id, $user);
                    return $participants;
                })->toJson(true);
        }
        return $this->renderView('SulRadio::backend.ticket.management');
    }
    public function deleteTickets(Request $request){
        $user       = Auth::user();
        $hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query      = $this->makeQuery($request, $user, $hasAdmin);
        $limit      = 10;
        $tickets    = $query->limit($limit)->get();
        foreach ($tickets as $ticket){
            /** Deleta todos os arquivos anexados ao ticket */
            $files = TicketDocument::getAllByTicketId($ticket->id, 1);
            foreach ($files as $file){
                TicketDocument::removeById($file->id, true);
            }
            /** Remove os comentários*/
            TicketComment::where('ticket_id', $ticket->id)->delete();
            /** Remove as notificaoes*/
            TicketNotification::where('ticket_id', $ticket->id)->delete();
            /** Remove os participantes*/
            TicketParticipant::where('ticket_id', $ticket->id)->delete();
            /** Remove os notificacoes para o clientes*/
            $notificationsClient = TicketNotificationClient::where('ticket_id', $ticket->id)->get();
            foreach ($notificationsClient as $notificationClient){
                /** Remove os notificacoes para o clientes*/
                TicketNotificationClientUser::where('ticket_notification_client_id', $notificationClient->id)->delete();
            }
            TicketNotificationClient::where('ticket_id', $ticket->id)->delete();
            TicketUrlTracker::where('ticket_id', $ticket->id)->delete();
            SystemLog::insertLogTicket(SystemLog::TYPE_DELETE_TICKET, "Ticket[{$ticket->id}] deletado do sistema", null, $user->id);
            $ticket->delete();
        }
        return response()->json(['mens'=>"Deletado {$limit} tickets"]);
    }


    public function changeParticipant(Request $request){
        $user       = Auth::user();
        $hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query      = $this->makeQuery($request, $user, $hasAdmin);
        $tickets    = $query->get();
        $participants = $request->get('participants');
        foreach ($tickets as $ticket){
            /** Remove os participantes*/
            TicketParticipant::where('ticket_id', $ticket->id)->delete();
            foreach ($participants as $participant){
                TicketParticipant::create(['user_id'=>$participant, 'ticket_id'=>$ticket->id]);
            }
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi modificado para os participants[".implode(',', $participants)."]", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }

    public function changeStatus(Request $request){
        $user           = Auth::user();
        $hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query          = $this->makeQuery($request, $user, $hasAdmin);
        $tickets        = $query->get();
        $status         = $request->get('status');
        $statusFinished = TicketStatus::statusFinished();
        foreach ($tickets as $ticket){
            $ticket->status_id = $status;
            if($status == $statusFinished->id){
                $ticket->completed_at = date('Y-m-d H:i:s');
            }else{
                $ticket->completed_at = null;
            }
            $ticket->save();
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi modificado para os status[{$status}]", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }
    public function changecategory(Request $request){
        $user           = Auth::user();
        $hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query          = $this->makeQuery($request, $user, $hasAdmin);
        $tickets        = $query->get();
        $category        = $request->get('category');
        foreach ($tickets as $ticket){
            $ticket->category_id = $category;
            $ticket->save();
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi modificado para a categoria[{$category}]", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }
    public function changeRequester(Request $request){
        $user           = Auth::user();
        $hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query          = $this->makeQuery($request, $user, $hasAdmin);
        $tickets        = $query->get();
        $requesterId    = $request->get('change_requester_id');
        $owner          = User::getByIdStatic($requesterId);
        foreach ($tickets as $ticket){
            $ticket->owner_id = $requesterId;
            $ticket->save();
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi modificado o solicitante para [{$requesterId}] [{$owner->name}]", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }
    public function changeShowClient(Request $request){
        $user           = Auth::user();
        $hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query          = $this->makeQuery($request, $user, $hasAdmin);
        $tickets        = $query->get();
        foreach ($tickets as $ticket){
            $ticket->show_client = 1;
            $ticket->save();
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi alterada para mostrar para o cliente", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }

    public function changeHideClient(Request $request){
        $user           = Auth::user();
        $hasAdmin       = ResourceAdmin::hasResourceByRouteName('ticket.admin');
        $query          = $this->makeQuery($request, $user, $hasAdmin);
        $tickets        = $query->get();
        foreach ($tickets as $ticket){
            $ticket->show_client = 0;
            $ticket->save();
            SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, "Ticket[{$ticket->id}] foi alterada para não mostrar para o cliente", null, $user->id);
        }
        return response()->json(['mens'=>"Tickets atualizados com sucesso"]);
    }

    protected function makeQuery(Request $request, $user, $hasAdmin ){
        $query      = Ticket::query()
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

        if($request->get('subject')){
            $query->where('subject', 'like', '%'.$request->get('subject').'%');
        }
        if($request->get('category_id')){
            $query->where('category_id',  $request->get('category_id'));
        }
        if($request->get('identify')){
            $query->where('ticket.id',  $request->get('identify'));
        }
        if($request->get('active')=='1'){
            $query->whereNull('completed_at');
        }
        if($request->get('show_client')=='1' || $request->get('show_client')=='0'){
            $query->where('show_client', $request->get('show_client'));
        }
        if($request->get('active')=='-1'){
            $query->whereNotNull('completed_at');
        }
        if($request->get('status_id')){
            $query->where('status_id',  $request->get('status_id'));
        }
        if($request->get('emissora_id')){
            $query->where('emissora_id',  $request->get('emissora_id'));
        }
        if($request->get('requester_id')){
            $query->where('owner_id',  $request->get('requester_id'));
        }
        if($request->get('participants_id')){
            $query->whereIn('ticket_participant.user_id',  $request->get('participants_id'));
        }
        return $query;
    }

	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$users = UserSulRadio::whereNull('client_id')
			->when((isset($user->users_ticket) && count($user->users_ticket)), function ($query) use($user) {
				$usersAllow = $user->users_ticket;
				$usersAllow[]=(int)$user->id;
				return $query->whereIn('id', $usersAllow);
			})
			->orderBy('name')->get();
		foreach ($users as &$userChange){
			$userInfoArray= [];
			if(isset($userChange->users_ticket) && count($userChange->users_ticket)){
				$usersInfo = UserSulRadio::whereIn('id', $userChange->users_ticket)->get();
				foreach ($usersInfo as $userInfo){
					$userInfoArray[]=['name'=>$userInfo->name.' '.$userInfo->lastname, 'id'=>$userInfo->id];
				}
			}
			$userChange->user_info = $userInfoArray;
		}

		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('ticket.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('ticket.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('ticket.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('ticket.update', [1]),
			'hasSendNotification' => ResourceAdmin::hasResourceByRouteName('ticket.comment.send.email'),
			'status' => TicketStatus::getWithCache(),
			'statusFinished' => TicketStatus::statusFinished(),
			'priority' => TicketPriority::getWithCache(),
			'category' => TicketCategory::getWithProfile($user),
			'users' => $users,
			'user'  => Auth::user()
		];
		$this->parameters = $parameters;
	}
}
