<?php

namespace Oka6\SulRadio\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TicketUrlTracker extends Model {
	const TABLE = 'ticket_url_tracker';
	protected $fillable = [
		'id',
		'ticket_id',
		'name',
		'description',
		'url',
		'last_modify',
		'last_tracker',
		'hash',
		'status',
	];
	protected $dates = ['created_at', 'updated_at', 'last_modify', 'last_tracker'];
	public function scopeWithTicket($query) {
		return $query->join('ticket', 'ticket.id', 'ticket_url_tracker.ticket_id');
	}

	public function scopeActive($query) {
		return $query->where('ticket_url_tracker.status', 1);
	}

	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getNew() {
		return self::active()->whereNull('last_modify')->get();
	}

	public static function getToCheck() {
		return self::active()->where('last_tracker', '<',  Carbon::now()->subHour(18))->get();
	}

	protected $connection = 'sulradio';
	protected $table = 'ticket_url_tracker';

	
}
