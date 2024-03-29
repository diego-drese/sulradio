<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Finalidade extends Model {
	const TABLE = 'finalidade';
	protected $fillable = [
		'finalidadeID',
		'desc_finalidadeID',
	];
	protected $connection = 'sulradio';
	protected $table = 'finalidade';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('finalidade', 120, function () {
			return self::get();
		});
	}
	
	
}
