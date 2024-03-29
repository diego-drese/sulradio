<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TipoEmissora extends Model {
	const TABLE = 'tipo_emissora';
	protected $fillable = [
		'tipo_emissoraID',
		'desc_tipo_emissora',
	];
	protected $connection = 'sulradio';
	protected $table = 'tipo_emissora';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('tipo_emissora', 120, function () {
			return self::get();
		});
	}
	
	
}
