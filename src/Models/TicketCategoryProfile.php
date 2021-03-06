<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TicketCategoryProfile extends Model {
	const TABLE = 'ticket_category_profile';
	protected $fillable = [
		'ticket_category_id',
		'profile_id',
		'user_id',
		'user_id_removed',
	];
	protected $table = 'ticket_category_profile';
	protected $connection = 'sulradio';
	


	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_category_profile-', 120, function ()  {
			return self::where('user_id_removed', 0)
				->get();
		});
	}
	public static function getProfilesByTicketCategoryId($id) {
		return self::where('ticket_category_id', $id)
			->where('user_id_removed', 0)
			->get()
			->pluck('profile_id')
			->toArray();
	}
	public static function createFromForm($request, $ticketCategory, $user){
		
		self::where('ticket_category_id', $ticketCategory->id)->update(['user_id_removed'=>$user->id]);
		$profilesId = $request->get('profile_id', []);
		foreach ($profilesId as $id){
			self::create([
				'ticket_category_id'    => $ticketCategory->id,
				'profile_id'            => $id,
				'user_id'               => $user->id,
			]);
		}
		
	}
}
