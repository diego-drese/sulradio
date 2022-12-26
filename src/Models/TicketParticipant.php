<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Oka6\Admin\Models\User;

class TicketParticipant extends Model {
	const TABLE = 'ticket_participant';
	protected $fillable = [
		'user_id',
		'ticket_id',
	];
	protected $table = 'ticket_participant';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function insertByController(Request $request, Ticket $ticket, $userLogged, $typeNotification) {
		$participants = $request->get('participants_id', []);
		if($participants && is_array($participants)){
			TicketParticipant::removeByTicket($ticket->id);
			foreach ($participants as $participant){
				if(count($participants)==1 || $participant!=$ticket->owner_id ){
					/**Adiciona o participante ao ticket */
					TicketParticipant::create([
						'user_id'=>$participant,
						'ticket_id'=>$ticket->id,
					]);
				}
			}
		}

		self::notifyParticipants($ticket, $userLogged, $typeNotification);
	}

	public static function notifyParticipants(Ticket $ticket, $userLogged, $typeNotification, $commentId=null, $daysDeadLine=null) {
		$participants = TicketParticipant::getUserByTicketId($ticket->id);
		$insert = [
			'type'              => $typeNotification,
			'ticket_id'         => $ticket->id,
			'user_logged'       => $userLogged->id,
			'comment_id'        => $commentId,
			'owner_id'          => $ticket->owner_id,
			'users_comments'    => null,
			'status'            => TicketNotification::STATUS_WAITING,
		];
		$contentLog='Não definido,['.$typeNotification.']';
		$logType=SystemLog::TYPE_UNDEFINED;
        $forceNotification = false;
		if($typeNotification==TicketNotification::TYPE_NEW){
			$contentLog = 'Usuário '.$userLogged->name. ' criou o ticket '. $ticket->id;
			$logType = SystemLog::TYPE_NEW;
		}else if($typeNotification==TicketNotification::TYPE_UPDATE){
			$contentLog = 'Usuário '.$userLogged->name. ' atualizou o ticket '. $ticket->id;
			$logType = SystemLog::TYPE_UPDATE;
		}else if($typeNotification==TicketNotification::TYPE_DEADLINE){
			$contentLog = 'Ticket com prazo de execuçao['.$ticket->start_forecast.'] encontado dentro do prazo estabelicido. ['.$daysDeadLine.']dias para iniciar';
			$logType = SystemLog::TYPE_DEADLINE;
		}else if($typeNotification==TicketNotification::TYPE_PROTOCOL_DEADLINE){
            $contentLog = 'Ticket com protocolo de entrega['.$ticket->end_forecast.'] encontado dentro do prazo estabelicido. ['.$daysDeadLine.']dias para o prazo do protocolo';
            $logType = SystemLog::TYPE_PROTOCOL_DEADLINE;
		}else if($typeNotification==TicketNotification::TYPE_COMMENT_CLIENT){
			$contentLog = 'Usuário '.$userLogged->name. ' respondeu a um comentário '. $ticket->id;
			$logType = SystemLog::TYPE_SEND_EMAIL_NOTIFICATION;
            $forceNotification=true;
		}else{
			$contentLog = 'Usuário '.$userLogged->name. ' adicionou um comentário ao ticket '. $ticket->id;
			$logType = SystemLog::TYPE_COMMENT;
		}

		if(!in_array($ticket->owner_id, $participants)){
			$participants[]=$ticket->owner_id;
		}

		foreach ($participants as $participant){
			/** Checks if there is a notification of the same ticket for the user  */
			$userParticipant            = UserSulRadio::getByIdStatic($participant);
			$insert['agent_current_id'] = $participant;
			$insert['agent_old_id']     = $participant;
			$userTickets                = !isset($userParticipant->users_ticket) ? [] : $userParticipant->users_ticket;

			if($userLogged->id!=$participant && ($forceNotification || !count($userTickets) || in_array($userLogged->id, $userTickets)) ){
				TicketNotification::create($insert);
			}

			if(!count($userTickets) || in_array($userLogged->id, $userTickets)){
				SystemLog::insertLogTicket($logType, $contentLog, $ticket->id, $participant);
			}
		}
	}

	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getUserByTicketId($id, $data=false, $user=null) {
		$query = self::where('ticket_id', $id);
		if($user && isset($user->users_ticket) && count($user->users_ticket)){
			$usersId = $user->users_ticket;
			$usersId[]= (int)$user->id;
			$query->whereIn('user_id', $usersId);
		}
		$result = $query->pluck('user_id')->toArray();
		if($data){
			$query = User::whereIn('id', $result);
			return $query->get();
		}
		return $result;
	}
	public static function getUserNameByTicketId($id, $user=null) {
		$usersId = self::getUserByTicketId($id, null, $user);
		return User::whereIn('id', $usersId )->pluck('name')->toArray();
	}
	public static function removeByTicket($id) {
		return self::where('ticket_id', $id)->delete();
	}
	
	
	
}
