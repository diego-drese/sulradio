<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TipoPenalidade extends Model {
	const TABLE = 'tipo_penalidade';
	protected $fillable = [
		'tipo_penalidadeID',
		'desc_tipo_penalidade',
	
	];
	protected $connection = 'sulradio';
	protected $table = 'tipo_penalidade';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ipo_penalidade', 120, function () {
			return self::get();
		});
	}
	
	
}
