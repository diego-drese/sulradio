<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {
	const TABLE = 'plan';
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
	protected $table = 'plan';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('id',$id)->first();
	}
	public static function getAll() {
		return self::get();
	}
}
