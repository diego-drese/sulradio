<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TipoAto extends Model {
	const TABLE = 'tipo_ato';
	protected $fillable = [
		'tipo_atoID',
		'desc_tipo_ato',
		'orgaoID',
	];
	protected $connection = 'sulradio';
	protected $table = 'tipo_ato';
	public $timestamps = false;
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('tipo_ato', 120, function () {
			return self::get();
		});
	}
	
	
}
