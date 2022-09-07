<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AtoNotification extends Model {
	const TABLE = 'ato_notification';
	protected $fillable = [
		'ato_id',
		'user_logged',
		'user_id',
		'total_send',
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
		self::STATUS_ANSWERED=> 'Respondida',
		self::STATUS_UNDEFINED => 'Indefinido',
		];

	protected $table = 'ato_notification';
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
		}
		return (object)$retval;
	}

	public static function getToNotify() {
		$notifications = self::where('status', self::STATUS_WAITING)
			->where('updated_at', '<', Carbon::now()->subMinutes(3)->toDateTimeString())
			->limit(50)
			->get();

		return $notifications;
	}
	
	
}
