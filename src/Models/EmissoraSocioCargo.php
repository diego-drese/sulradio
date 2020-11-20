<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraSocioCargo extends Model {
	const TABLE = 'emissora_socio_cargo';
	public $timestamps = false;
	protected $fillable = [
		'cargoID',
		'desc_cargo',
		'tipo_emissoraID'
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_socio_cargo';
	protected $primaryKey = 'cargoID';
	
	public static function getById($id) {
		return self::where('cargoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_socio_cargo', 10, function () {
			return self::get();
		});
	}
	
}
