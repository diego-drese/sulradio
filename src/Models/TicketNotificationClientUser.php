<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TicketNotificationClientUser extends Model {
	const TABLE = 'ticket_notification_client_user';
	protected $fillable = [
		'identify',
		'ticket_notification_client_id',
		'user_id',
		'user_name',
		'user_email',
		'answer',
		'answer_date_at',
		'answer_file_1',
		'answer_file_2',
		'answer_file_3',
		'answer_file_4',
		'answer_file_5',
		'answer_file_6',
		'answer_file_7',
		'answer_file_8',
		'answer_file_9',
		'answer_file_10',
		'status',
	];
	const STATUS_WAITING = 1;
	const STATUS_SENT = 2;
	const STATUS_ANSWERED = 3;
	const STATUS_ERROR = 98;
	const STATUS_UNDEFINED = 99;

	const STATUS_TEXT = [
		self::STATUS_WAITING => 'Aguardando envio',
		self::STATUS_SENT => 'Enviado',
		self::STATUS_ANSWERED=> 'Respondido',
		self::STATUS_ERROR=> 'Erro ao enviar',
		self::STATUS_UNDEFINED => 'Indefinido',
	];

	protected $table = 'ticket_notification_client_user';
	protected $connection = 'sulradio';

	public static function getStatusText($status){
		return self::STATUS_TEXT[$status] ?? self::STATUS_TEXT[self::STATUS_UNDEFINED];
	}

	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}

	public function getCreatedAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}

	public function getSendDateAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}

	public function getAnswerDateAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}
	public function getStatusAttribute($value) {
		return $value ? self::getStatusText($value)  : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getByIdentify($id) {
		return self::where('identify', $id)->first();
	}

	public static function getByTicketNotificationClientId($id, $getPicture=false, $getAttach=false) {
		$query = self::where('ticket_notification_client_id', $id)->get();
		if($query){
			foreach ($query as &$value){
				if($getPicture){
					$user = UserSulRadio::getByIdStatic($value->user_id);
					$value->user_picture = $user->picture??'';
				}
				$attach=[];
				if($getAttach){
					for ($i=1; $i<11; $i++){
						$answerFile = "answer_file_{$i}";
						if($value->$answerFile){
							$attach[] = TicketDocument::getById($value->$answerFile);
						}
					}
				}
				$value->attach=$attach;
			}
		}
		return $query;
	}

	
	
}
