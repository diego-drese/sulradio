<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Model;

class States extends Model {
	const TABLE = 'states';
	protected $fillable = [
		'name',
		'description',
		'max_upload',
		'max_broadcast',
		'max_user',
		'value',
		'frequency',
		'is_active'
	];
	protected $table = 'states';
	protected $connection = 'sulradio_mongo';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('_id', new \MongoDB\BSON\ObjectId($id))->first();
	}
	public static function getByIdWithCache($id) {
		return Cache::tags(['sulradio'])->remember('state-'.$id, 120, function () use($id) {
			return self::where('id', (int)$id)->first();
		});
		
	}
}
