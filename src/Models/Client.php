<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

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
	protected $connection = 'sulradio_mongo';
	
	public function getValidatedAtAttribute($value) {
		return $value ? (new Carbon($value->toDateTime()))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('_id', new \MongoDB\BSON\ObjectId($id))->first();
	}
}
