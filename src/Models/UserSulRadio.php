<?php

namespace Oka6\SulRadio\Models;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Library\MongoUtils;
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
		'last_login_at',
		'last_notification_at',
		'receive_notification',
		'client_id',
		'broadcast',
		'function_id',
		'users_ticket',
		'user_created_id',
		'user_updated_id',
		'user_updated_at',
		'remember_token',
		'description',
	];
	public function scopeClient($query, $clientId){
		return $query->where('client_id',  (int)$clientId);
	}
	
	public static function getBy_Id($userId){
		return self::where('_id',  new \MongoDB\BSON\ObjectId($userId))->first();
	}

    public static function getUsersByEmissoraId($emissoraId, $clientId){
        return self::whereIn('broadcast',  [(string)$emissoraId])
            ->where('active', 1)
            ->where('client_id', (int)$clientId)
            ->get();
	}
    public static function getUsersClientId($clientId){
        return self::where('client_id', (int)$clientId)
            ->where('active', 1)
            ->where(function ($query) {
                $query->where('broadcast', '$exists', false)
                    ->orWhere('broadcast', '$eq',  []);
            })
            ->get();
	}

	public function getLastNotificationAtAttribute($value) {
		return $value && !empty($value) ? MongoUtils::convertDateMongoToPhpDateTime($value)->format('d/m/Y H:i') : '---';
	}

	public function getWithCache() {
		return Cache::tags(['sulradio'])->remember('funcao', 10, function () {
			return self::get();
		});
	}
	
}
