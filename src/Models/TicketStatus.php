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
		'color',
	];
	protected $table = 'ticket_status';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_status-', 120, function ()  {
			return self::where('is_active', 1)
				->orderBy('name', 'asc')
				->get();
		});
	}
}
