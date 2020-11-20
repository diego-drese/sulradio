<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraSocioCategoria extends Model {
	const TABLE = 'emissora_socio_categoria';
	public $timestamps = false;
	protected $fillable = [
		'categoriaID',
		'desc_categoria',
		'idclassificacao',
		'tipo_emissoraID',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_socio_categoria';
	protected $primaryKey = 'categoriaID';
	
	public static function getById($id) {
		return self::where('categoriaID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_socio_categoria', 10, function () {
			return self::get();
		});
	}
	
}
