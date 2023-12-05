<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TicketNotificationClient extends Model {
	const TABLE = 'ticket_notification_client';
	protected $fillable = [
		'type',
		'ticket_id',
		'user_id',
		'comment_id',
		'comment',
		'send_file_1',
		'send_file_2',
		'send_file_3',
		'send_file_4',
		'send_file_5',
		'send_file_6',
		'send_file_7',
		'send_file_8',
		'send_file_9',
		'send_file_10',
		'total_send',
		'total_answered',
		'status',
	];
	const STATUS_WAITING_CONFIRM = 0;
	const STATUS_WAITING = 1;
	const STATUS_PROCESSING = 2;
	const STATUS_SEND = 3;
	const STATUS_ANSWERED = 4;
	const STATUS_UNDEFINED = 99;

	const STATUS_TEXT = [
		self::STATUS_WAITING_CONFIRM => 'Aguardando confirmação',
		self::STATUS_WAITING => 'Aguardando disparo',
		self::STATUS_PROCESSING=> 'Processando',
		self::STATUS_SEND=> 'Enviado',
		self::STATUS_ANSWERED=> 'Respondido',
		self::STATUS_UNDEFINED => 'Indefinido',
		];

	const TYPE_INFO = 1;
	const TYPE_QUESTION = 2;

	protected $table = 'ticket_notification_client';
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
	public function getStatusAttribute($value) {
		return $value ? self::getStatusText($value)  : '';
	}

	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function hasExist($id) {
		return self::where('comment_id', $id)->first();
	}
	public static function getByParseToFront($id) {
		$retval = [];
		$query = self::getByCommentId($id);
		if($query){
			$user = UserSulRadio::getByIdStatic($query->user_id);
			$retval['id']=$query->id;
			$retval['user_id']=$query->user_id;
			$retval['user_name']=$user->name.' '.$user->lastname;
			$retval['status']=$query->status;
			$retval['created_at']=$query->created_at;
			$retval['updated_at']=$query->updated_at;
			$retval['total_send']=$query->total_send;
			$retval['total_answered']=$query->total_answered;
			$retval['answered']=TicketNotificationClientUser::getByTicketNotificationClientId($query->id, true, true);
		}
		return (object)$retval;
	}
	public static function getByCommentId($id) {
		return self::where('comment_id', $id)->first();
	}
	public static function getToNotify() {
		$notifications = self::where('status', self::STATUS_WAITING)
			->where('updated_at', '<', Carbon::now()->subMinutes(3)->toDateTimeString())
			->limit(50)
			->get();
		if($notifications){
			self::whereIn('id', $notifications->pluck('id')->toArray())->update(['status'=>self::STATUS_PROCESSING]);
			foreach ($notifications as &$notify){
				$attach=[];
				for ($i=1; $i<11; $i++){
					$sendFile = "send_file_{$i}";
					if($notify->$sendFile){
						$attach[] = TicketDocument::getById($notify->$sendFile);
					}
				}
				$notify->attach=$attach;
			}
		}
		return $notifications;
	}
	
	
}
