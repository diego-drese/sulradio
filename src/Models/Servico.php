<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Servico extends Model {
	const TABLE = 'servico';
	protected $fillable = [
		'servicoID',
		'desc_servico',
		'classificacaoID',
		'validade_outorga',
	];
	protected $connection = 'sulradio';
	protected $table = 'servico';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('servicoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('servico', 10, function () {
			return self::get();
		});
	}
	
}
