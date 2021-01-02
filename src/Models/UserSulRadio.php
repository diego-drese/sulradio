<?php

namespace Oka6\SulRadio\Models;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Models\User;

class UserSulRadio extends User {
	protected $fillable = [
		'id',
		'name',
		'email',
		'password',
		'lastname',
		'cell_phone',
		'active',
		'profile_id',
		'resource_default_id',
		'picture',
		'type',
		'client_id',
		'description',
	];
	public function scopeClient($query, $clientId){
		return $query->where('client_id',  $clientId);
	}
	
	public static function getBy_Id($userId){
		return self::where('_id',  new \MongoDB\BSON\ObjectId($userId))->first();
	}
	
	public function getWithCache() {
		return Cache::tags(['sulradio'])->remember('funcao', 10, function () {
			return self::get();
		});
	}
	
}
