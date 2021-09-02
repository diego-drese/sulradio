<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TicketNotification extends Model {
	const TABLE = 'ticket_notification';
	protected $fillable = [
		'type',
		'ticket_id',
		'agent_current_id',
		'agent_old_id',
		'user_logged',
		'owner_id',
		'comment_id',
		'users_comments',
		'status',
	];
	const STATUS_WAITING = 0;
	const STATUS_PROCESSING = 1;
	const STATUS_PROCESSED = 2;
	
	const TYPE_NEW = 1;
	const TYPE_UPDATE = 2;
	const TYPE_COMMENT = 3;
	const TYPE_TRANSFER_AGENT = 4;
	
	protected $table = 'ticket_notification';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getLastNotifications($id) {
		return self::where('id', $id)->paginate(15);
	}
	
	public static function getToNotify() {
		$notifications = self::where('status', self::STATUS_WAITING)
			->where('updated_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString())
			->limit(50)
			->get();
		if($notifications){
			self::whereIn('id', $notifications->pluck('id')->toArray())->update(['status'=>self::STATUS_PROCESSING]);
		}
		return $notifications;
		
	}
	
	
}
