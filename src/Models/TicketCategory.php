<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;

class TicketCategory extends Model {
	const TABLE = 'ticket_category';
	protected $fillable = [
		'name',
		'description',
		'is_active',
		'color',
	];
	protected $table = 'ticket_category';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_category-', 120, function ()  {
			return self::where('is_active', 1)
				->orderBy('name', 'asc')
				->get();
		});
	}
	
	public static function getWithProfile($user) {
		$hasAdmin   = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$query      = self::where('is_active', 1)->orderBy('name', 'asc');
		if(!$hasAdmin){
			$query->select('ticket_category.*')
				->join('ticket_category_profile', 'ticket_category_profile.ticket_category_id', 'ticket_category.id')
				->where('profile_id', $user->profile_id)
				->where('user_id_removed', 0);
		}
		return $query->get();
	}
}
