<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraProcessoFase extends Model {
	const TABLE = 'emissora_processo_fase';
	protected $fillable = [
		'processo_faseID',
		'desc_processo_fase',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_processo_fase';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('processo_faseID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_processo_fase', 10, function () {
			return self::get();
		});
	}
	
}
