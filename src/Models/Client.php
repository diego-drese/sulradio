<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Client extends Model {
	const TABLE = 'client';
	protected $fillable = [
		'name',
		'company_name',
		'email',
		'document_type',
		'document',
		'cellphone',
		'telephone',
		'is_active',
		'street',
		'address_number',
		'complement',
		'city_id',
		'city_name',
		'neighborhood',
		'zip_code',
		'description',
		
		'plan_id',
		'users',
		'uploads_gb',
		'broadcast',
		'validated_at',
	];
	protected $table = 'client';
	protected $connection = 'sulradio';
	
	public function getValidatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getUsersByEmissora($id) {
		return Cache::tags(['sulradio'])->remember('users_by_emissora-'.$id, 10, function () use($id){
			$emissora       = Emissora::getByIdOnly($id);
			if($emissora && $emissora->client_id){
                $users          = [];
                $userAppend     = [];
                $userByEmissora = UserSulRadio::getUsersByEmissoraId($id, $emissora->client_id);
                $userByClient   = UserSulRadio::getUsersClientId($emissora->client_id);
                foreach ($userByEmissora as $user){
                    if(!isset($userAppend[$user->id])){
                        $userAppend[$user->id]=true;
                        $users[] = $user;
                    }
                }
                foreach ($userByClient as $user){
                    if(!$user->broadcast && !isset($userAppend[$user->id])){
                        $userAppend[$user->id]=true;
                        $users[] = $user;
                    }
                }

				return $users;
			}
			return [];
		});
	}
}
