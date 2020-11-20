<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;

class Municipio extends Model {
	const TABLE = 'municipio';
	public $timestamps = false;
	protected $fillable = [
		'municipioID',
		'desc_municipio',
		'ufID'
	];
	protected $connection = 'sulradio';
	protected $table = 'municipio';
	
	public static function getById($id) {
		return self::where('municipioID', $id)
			->join('uf', 'uf.ufID', 'municipio.ufID')
			->first();
	}
	
	
}
