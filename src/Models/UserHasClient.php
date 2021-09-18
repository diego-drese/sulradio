<?php

namespace Oka6\SulRadio\Models;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class UserHasClient extends Model{
	const TABLE = 'user_has_client';
	protected $fillable = [
		'id',
		'user_id',
		'client_id',
		'create_user_id',
		'update_user_id',
	];
	protected $table = 'user_has_client';
	protected $connection = 'sulradio';
	public static function getAllClients($userId){
		return self::whereNull('update_user_id')
			->select('user_has_client.*', 'client.name as client_name', 'client.company_name as company_name')
			->join('client', 'client.id', 'user_has_client.client_id')
			->where('user_has_client.user_id', $userId)
			->get();
	}
	public static function createOrUpdate($clients, $userId, $userLogged){
		self::where('user_id', $userId)->update(['update_user_id'=>$userLogged->id]);
		if(is_array($clients)){
			foreach ($clients as $client){
				self::create(['user_id'=>$userId,'client_id'=>$client, 'create_user_id'=>$userLogged->id ]);
			}
		}

	}

	public static  function getEmissorasWithCache($userId) {
		return Cache::tags(['sulradio'])->remember('userHasEmissoras-'.$userId, 60, function () use($userId){
			$clients =  self::where('user_id', $userId)->whereNull('update_user_id')->get();
			if(!count($clients)){
				return [];
			}
			return Emissora::whereIn('client_id', $clients->pluck('client_id')->toArray())->get()
				->pluck('emissoraID')
				->toArray();
		});
	}
	
}
