<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class Countries extends Model {
	const TABLE = 'countries';
	protected $fillable = [
		'id',
		'title',
	];
	protected $table = 'countries';
	protected $connection = 'sulradio_mongo';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('_id', new \MongoDB\BSON\ObjectId($id))->first();
	}
}
