<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraTipoIngresso extends Model {
	const TABLE = 'emissora_socio_tipo_ingresso';
	protected $fillable = [
		'tipo_ingressoID',
		'desc_tipo_ingresso',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_socio_tipo_ingresso';
	protected $primaryKey = 'tipo_ingressoID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('tipo_ingressoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_socio_tipo_ingresso', 10, function () {
			return self::get();
		});
	}
	
}
