<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ReferencialPenalidade extends Model {
	const TABLE = 'referencia_penalidade';
	protected $fillable = [
		'referencia_penalidadeID',
		'desc_ref_penalidadeID',
	];
	protected $connection = 'sulradio';
	protected $table = 'referencia_penalidade';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('referencia_penalidade', 120, function () {
			return self::get();
		});
	}
	
	
}
