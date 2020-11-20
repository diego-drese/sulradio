<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Funcao extends Model {
	const TABLE = 'funcao';
	public $timestamps = false;
	protected $fillable = [
		'funcaoID',
		'desc_funcao',
	];
	protected $connection = 'sulradio';
	protected $table = 'funcao';
	
	public static function getById($id) {
		return self::where('funcaoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('funcao', 10, function () {
			return self::get();
		});
	}
	
}
