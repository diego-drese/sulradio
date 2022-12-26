<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TicketStatus extends Model {
	const TABLE = 'ticket_status';
	protected $fillable = [
		'name',
		'description',
		'is_active',
		'update_completed_at',
		'color',
		'send_deadline',
	];
	protected $table                = 'ticket_status';
	protected $connection           = 'sulradio';
	const STATUS_DEADLINE           = 'deadline';
	const STATUS_PROTOCOL_DEADLINE  = 'protocol_deadline';

	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}

	public static function statusFinished($id= null) {
		return Cache::tags(['sulradio'])->remember('ticket_status_finished-'.$id, 120, function () use($id) {
			if($id){
				return self::where('id', $id)->where('update_completed_at', 1)->first();
			}
			return self::where('update_completed_at', 1)->first();
		});
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_status', 120, function ()  {
			return self::where('is_active', 1)
				->orderBy('name', 'asc')
				->get();
		});
	}

    public static function getStatusDeadLine($deadLineType) {
        return self::where('send_deadline', $deadLineType)->first();
	}
}
