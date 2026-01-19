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
	const STATUS_IGNORED = 3;

	const TYPE_NEW = 1;
	const TYPE_UPDATE = 2;
	const TYPE_COMMENT = 3;
	const TYPE_TRANSFER_AGENT = 4;
	const TYPE_COMMENT_CLIENT = 5;
	const TYPE_TRACKER_URL = 6;
	const TYPE_DEADLINE = 7;
	const TYPE_PROTOCOL_DEADLINE = 8;
	const TYPE_RENEWAL_ALERT = 9;
	const TYPE_UNDEFINED = 99;

    const TYPE_TRANSLATED = [
        self::TYPE_NEW=>'Novo',
        self::TYPE_UPDATE=>'Atualizado',
        self::TYPE_COMMENT=>'Comentário',
        self::TYPE_TRANSFER_AGENT=>'Transferência',
        self::TYPE_COMMENT_CLIENT=>'Cliente',
        self::TYPE_TRACKER_URL=>'Processo',
        self::TYPE_DEADLINE=>'Prazo ticket',
        self::TYPE_PROTOCOL_DEADLINE=>'Prazo protocolo',
        self::TYPE_RENEWAL_ALERT=>'Vencimento ticket',
        self::TYPE_UNDEFINED=>'Sem tipo',
    ];

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
    public static function checkAllNotifications($commentId) {
		return self::where('comment_id', $commentId)
            ->whereIn('status', [self::STATUS_WAITING, self::STATUS_PROCESSING])
            ->count();
	}
	
	public static function getToNotify() {
		$notifications = self::where('status', self::STATUS_WAITING)
			->limit(1000)
			->orderBy('id', 'desc')
			->get();
		if($notifications){
			self::whereIn('id', $notifications->pluck('id')->toArray())->update(['status'=>self::STATUS_PROCESSING]);
		}
		return $notifications;
		
	}
	
	
}
