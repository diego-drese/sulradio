<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StatusSead extends Model {
	const TABLE = 'status_sead';
	protected $fillable = [
		'status_seadID',
		'desc_status_sead',
	];
	protected $connection = 'sulradio';
	protected $table = 'status_sead';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('status_sead', 120, function () {
			return self::get();
		});
	}
	
	
}
