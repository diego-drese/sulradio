<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EstadoCivil extends Model {
	const TABLE = 'estado_civil';
	public $timestamps = false;
	protected $fillable = [
		'estado_civilID',
		'desc_estado_civil',
	];
	protected $connection = 'sulradio';
	protected $table = 'estado_civil';
	protected $primaryKey = 'estado_civilID';
	
	public static function getById($id) {
		return self::where('estado_civilID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('estado_civil', 10, function () {
			return self::get();
		});
	}
	
	
	
}
