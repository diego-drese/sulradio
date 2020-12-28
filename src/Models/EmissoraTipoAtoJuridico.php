<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraTipoAtoJuridico extends Model {
	const TABLE = 'emissora_tipo_ato_juridico';
	protected $fillable = [
		'tipo_ato_juridicoID',
		'desc_tipo_ato_juridico',
		'orgaoID',
		'idtopico',
		'ordem',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_tipo_ato_juridico';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('tipo_ato_juridicoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_tipo_ato_juridico', 10, function () {
			return self::get();
		});
	}
	
}
