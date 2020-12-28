<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraTipoEndereco extends Model {
	const TABLE = 'emissora_tipo_endereco';
	protected $fillable = [
		'tipo_enderecoID',
		'desc_tipo_endereco'
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_tipo_endereco';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_tipo_endereco', 120, function () {
			return self::get();
		});
	}
	
	
}
