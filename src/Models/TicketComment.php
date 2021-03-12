<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Models\User;

class TicketComment extends Model {
	const TABLE = 'ticket_comment';
	protected $fillable = [
		'content',
		'html',
		'user_id',
		'ticket_id',
	];
	protected $table = 'ticket_comment';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : '';
	}
	public function getCreatedAtAttribute($value) {
		return $value ? (new Carbon($value))->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getAllByTicketId($id) {
		$comments = self::where('ticket_id', $id)
			->orderBy('created_at')
			->get();
		foreach ($comments as &$comment){
			$user = User::getByIdStatic($comment->user_id);
			$comment->user_name = $user->name;
			$comment->user_picture = $user->picture;
		}
		return $comments;
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_category-', 120, function ()  {
			return self::where('is_active', 1)
				->orderBy('name', 'asc')
				->get();
		});
	}
}
