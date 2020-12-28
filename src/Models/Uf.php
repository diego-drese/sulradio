<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Uf extends Model {
	const TABLE = 'uf';
	protected $fillable = [
		'ufID',
		'desc_uf',
		'uf_extenso',
	];
	protected $connection = 'sulradio';
	protected $table = 'uf';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('uf', 120, function () {
			$ufs = self::get();
			foreach ($ufs as &$uf) {
				$uf->municipios = Municipio::where('ufID', $uf->ufID)->get()->toArray();
			}
			return $ufs;
		});
	}
	
	
}
