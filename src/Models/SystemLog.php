<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Oka6\SulRadio\Helpers\Helper;

class SystemLog extends Model {
	const TABLE = 'system_log';
	protected $fillable = ['subject', 'content', 'type', 'ticket_id', 'user_id', 'only_root', 'status'];
	const STATUS_NEW  = 1;
	const STATUS_READ  = 2;
	const STATUS_UNDEFINED  = 99;

	const TYPE_NEW = 1;
	const TYPE_UPDATE = 2;
	const TYPE_COMMENT = 3;
	const TYPE_TRANSFER_AGENT = 4;
	const TYPE_VIEW = 5;
	const TYPE_SEND_EMAIL_NOTIFICATION = 6;
	const TYPE_SAVE_UPLOAD = 7;
	const TYPE_DELETE_UPLOAD = 8;
	const TYPE_SAVE_TRACKER_URL = 9;
	const TYPE_DELETE_TRACKER_URL = 10;
	const TYPE_UNDEFINED = 99;

	const ZONE_TICKET = 1;
	const ZONE_SEAD= 2;
	const ZONE_UNDEFINED= 99;

	const TYPE_TEXT = [
		self::TYPE_NEW => 'Novo',
		self::TYPE_UPDATE => 'Atualização',
		self::TYPE_COMMENT => 'Comentário',
		self::TYPE_TRANSFER_AGENT => 'Tranferência',
		self::TYPE_VIEW => 'Visualização',
		self::TYPE_SAVE_UPLOAD => 'Upload',
		self::TYPE_DELETE_UPLOAD => 'Deletou arquivo',
		self::TYPE_SAVE_TRACKER_URL => 'Nova rastreador',
		self::TYPE_DELETE_TRACKER_URL => 'Deleta rastreador',
		self::TYPE_SEND_EMAIL_NOTIFICATION => 'Notificação Email',
		self::TYPE_UNDEFINED => 'Indefinido',
		];
	const ZONE_TEXT = [self::ZONE_TICKET => 'Ticket', self::ZONE_SEAD => 'SEAD', self::ZONE_UNDEFINED=> 'Indefinido',];
	const STATUS_TEXT = [self::STATUS_NEW => 'Novo', self::STATUS_READ => 'Lido', self::STATUS_UNDEFINED=> 'Indefinido',];

	protected $table = 'system_log';
	protected $connection = 'sulradio';

	public static function getTypeText($type){
		return self::TYPE_TEXT[$type] ?? self::TYPE_TEXT[self::TYPE_UNDEFINED];
	}

	public static function getZoneText($type){
		return self::ZONE_TEXT[$type] ?? self::ZONE_TEXT[self::TYPE_UNDEFINED];
	}

	public static function getStatusText($status){
		return self::STATUS_TEXT[$status] ?? self::STATUS_TEXT[self::STATUS_UNDEFINED];
	}

	public static function getById( $id ) {
		return self::where('id', $id)->first();
	}
	protected static function makeQueryLastNotifications($id , $zone, $hasAdmin=false){
		$query = self::where('user_id', $id)
			->where('zone', $zone)
			->orderBy('status', 'ASC')
			->orderBy('created_at', 'DESC');
		if(!$hasAdmin){
			$query->where('only_root', 0);
		}
		return $query;
	}

	protected static function getLastNotifications( $id , $zone, $hasAdmin=false) {
		$query = self::makeQueryLastNotifications($id , $zone, $hasAdmin);
		$result = $query->paginate(10);
		$result->total_unread = self::makeQueryLastNotifications($id , $zone, $hasAdmin)->where('status', 1)->count();
		return $result;
	}

	public static function getLastNotificationsTicket( $id , $hasAdmin=false) {
		return self::getLastNotifications($id, self::ZONE_TICKET, $hasAdmin);
	}

	public static function getNotificationsTicket( $id ) {
		return self::makeQueryLastNotifications($id, self::ZONE_TICKET, false);
	}
	public static function getNotifications($request) {
		$query = self::query();
		if($request->user_id){
			$query->where('user_id', $request->user_id);
		}if($request->status){
			$query->where('status', $request->status);
		}if($request->type){
			$query->where('type', $request->type);
		}if($request->zone){
			$query->where('zone', $request->zone);
		}if($request->period){
			$split = explode('-', $request->period);
			$query->where('created_at', '>=', Helper::convertDateBrToMysql(trim($split[0]).' 00:00:00', true));
			$query->where('created_at', '<=', Helper::convertDateBrToMysql(trim($split[1]).' 23:59:59', true));
		}
		return $query;
	}

	public static function insertLogTicket($type, $content, $ticketId = null, $userId = null){
		return self::insertLog(self::ZONE_TICKET, $type, $content, $ticketId, $userId);
	}

	public static function updateToRead($userID, $zone){
		$query = self::makeQueryLastNotifications($userID, $zone);
		return $query->update(['status'=>self::STATUS_READ]);
	}

	protected  static function insertLog($zone, $type, $content, $ticketId = null, $userId = null) {
		$data   = [
			'subject'=>self::getTypeText($type),
			'content'=> 'Tipo nao reconhecido',
			'zone' => $zone,
			'type' => $type,
			'status' => 1,
			'ticket_id' => $ticketId,
			'user_id' => $userId,
			'only_root' => 0
		];
		switch ($type) {
			case self::TYPE_NEW:
			case self::TYPE_UPDATE:
			case self::TYPE_COMMENT:
			case self::TYPE_TRANSFER_AGENT:
				$data['content'] = $content;
			break;
			case self::TYPE_SEND_EMAIL_NOTIFICATION:
			case self::TYPE_SAVE_UPLOAD:
			case self::TYPE_DELETE_UPLOAD:
			case self::TYPE_SAVE_TRACKER_URL:
			case self::TYPE_DELETE_TRACKER_URL:
			case self::TYPE_VIEW:
				$data['content'] = $content;
				$data['only_root'] = 1;
			break;
		}
		return self::create($data);
	}


}
