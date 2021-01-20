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
	protected $primaryKey = 'tipo_atoID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('tipo_ato', 120, function () {
			return self::get();
		});
	}
	public static function getOrCreateByName($name) {
		$tipo = self::where('desc_tipo_ato', $name)->first();
		if(!$tipo){
			$tipo = self::create([
				'desc_tipo_ato'=> mb_strtoupper($name, 'UTF-8'),
				'orgaoID'=>1,
			]);
		}
		
		Cache::tags('sulradio')->flush();
		return $tipo;
	}
	
	
}
