<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Contato extends Model {
	const TABLE = 'contato';
	public $timestamps = false;
	protected $fillable = [
		'contatoID',
		'nome_contato',
		'emissoraID',
		'funcaoID',
	];
	protected $connection = 'sulradio';
	protected $table = 'contato';
	protected $primaryKey = 'contatoID';
	
	public static function getById($id) {
		return self::where('contatoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('contato', 10, function () {
			return self::get();
		});
	}
	
	public function scopeWithFuncao($query) {
		$query->leftJoin('funcao', 'funcao.funcaoID', 'contato.funcaoID');
		return $query;
	}
	
	public function scopeWithEmissora($query, $emissoraID) {
		return $query->where('emissoraID', $emissoraID);
	}
	
}
