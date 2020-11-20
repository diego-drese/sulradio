<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TipoRepresentanteSocial extends Model {
	const TABLE = 'tipo_repres_social';
	public $timestamps = false;
	protected $fillable = [
		'tipo_repres_socialID',
		'desc_tipo_repres_social',
	];
	protected $connection = 'sulradio';
	protected $table = 'tipo_repres_social';
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('tipo_repres_social', 120, function () {
			return self::get();
		});
	}
	
	
}
