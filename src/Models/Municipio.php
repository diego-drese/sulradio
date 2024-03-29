<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;

class Municipio extends Model {
	const TABLE = 'municipio';
	protected $fillable = [
		'municipioID',
		'desc_municipio',
		'ufID'
	];
	protected $connection = 'sulradio';
	protected $table = 'municipio';
    protected $primaryKey = 'municipioID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('municipioID', $id)
			->join('uf', 'uf.ufID', 'municipio.ufID')
			->first();
	}
    public function scopeWithUf($query) {
        $query->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
        return $query;
    }
	
	
}
