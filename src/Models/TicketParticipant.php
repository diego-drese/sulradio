<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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

	public static function notifyParticipants(Ticket $ticket, $userLogged, $typeNotification, $commentId=null) {

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
		foreach ($participants as $participant){
			/** Checks if there is a notification of the same ticket for the user  */
			$insert['agent_current_id'] = $participant;
			$insert['agent_old_id']     = $participant;
			if($userLogged->id!=$participant){
				TicketNotification::create($insert);
			}

			if($typeNotification==TicketNotification::TYPE_NEW){
				$contentLog = 'Usuário '.$userLogged->name. ' criou o ticket '. $ticket->id;
				SystemLog::insertLogTicket(SystemLog::TYPE_NEW, $contentLog, $ticket->id, $participant);
			}else if($typeNotification==TicketNotification::TYPE_UPDATE){
				$contentLog = 'Usuário '.$userLogged->name. ' atualizou o ticket '. $ticket->id;
				SystemLog::insertLogTicket(SystemLog::TYPE_UPDATE, $contentLog, $ticket->id, $participant);
			}else{
				$contentLog = 'Usuário '.$userLogged->name. ' adicionou um comentário ao ticket '. $ticket->id;
				SystemLog::insertLogTicket(SystemLog::TYPE_COMMENT, $contentLog, $ticket->id, $participant);
			}

		}

		if($userLogged->id!=$ticket->owner_id){
			$insert['agent_current_id'] = $ticket->owner_id;
			$insert['agent_old_id']     = $ticket->owner_id;
			TicketNotification::create($insert);
		}
		
	}
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getUserByTicketId($id, $data=false) {
		$query = self::where('ticket_id', $id)->pluck('user_id')->toArray();
		if($data){
			$query = User::whereIn('id', $query);
			return $query->get();
		}
		return $query;
	}
	public static function getUserNameByTicketId($id) {
		$usersId = self::getUserByTicketId($id);
		return User::whereIn('id', $usersId )->pluck('name')->toArray();
	}
	public static function removeByTicket($id) {
		return self::where('ticket_id', $id)->delete();
	}
	
	
	
}
