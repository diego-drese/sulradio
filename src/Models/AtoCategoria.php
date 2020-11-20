<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AtoCategoria extends Model {
	const TABLE = 'ato_categoria';
	public $timestamps = false;
	protected $fillable = [
	
	];
	protected $connection = 'sulradio';
	protected $table = 'ato_categoria';
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ato_categoria', 120, function () {
			return self::get();
		});
	}
	
}
