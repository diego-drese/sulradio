<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class Cities extends Model {
	const TABLE = 'cities';
	protected $fillable = [
		'id',
		'state_id',
		'title',
		'iso',
		'iso_ddd',
		'status',
		'slug',
		'population',
		'lat',
		'long',
		'income_per_capita',
	];
	protected $table = 'cities';
	protected $connection = 'sulradio_mongo';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	public static function getById($id) {
		return self::where('id', (int)$id)->first();
	}
}
