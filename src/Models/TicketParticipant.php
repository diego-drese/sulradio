<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;

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
	public static function insertByController(Request $request, Ticket $ticket, $owner, $typeNotification) {
		$participants = $request->get('participants_id', []);
		if($participants && is_array($participants)){
			TicketParticipant::removeByTicket($ticket->id);
			foreach ($participants as $participant){
				if($participant!=$owner->id){
					/**Adiciona o participante ao ticket */
					TicketParticipant::create([
						'user_id'=>$participant,
						'ticket_id'=>$ticket->id,
					]);
				}
			}
		}
		self::notifyParticipants($ticket, $owner, $typeNotification);
	}
	public static function notifyParticipants(Ticket $ticket, $owner, $typeNotification, $commentId=null) {
		$participants = TicketParticipant::getUserByTicketId($ticket->id);
		foreach ($participants as $participant){
			/** Checks if there is a notification of the same ticket for the user  */
			$insert = [
				'type'              => $typeNotification,
				'ticket_id'         => $ticket->id,
				'agent_current_id'  => $participant,
				'agent_old_id'      => $participant,
				'user_logged'       => $owner->id,
				'comment_id'        => $commentId,
				'owner_id'          => $owner->id,
				'users_comments'    => null,
				'status'            => TicketNotification::STATUS_WAITING,
			];
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
