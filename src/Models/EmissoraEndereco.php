<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EmissoraEndereco extends Model {
	const TABLE = 'emissora_endereco';
	protected $fillable = [
		'enderecoID',
		'emissoraID',
		'tipo_enderecoID',
		'logradouro',
		'numero',
		'complemento',
		'bairro',
		'municipioID',
		'cep',
		'email',
		'url',
		'fone1',
		'fone2',
		'fone3',
		'fax',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_endereco';
	protected $primaryKey = 'enderecoID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('enderecoID', $id)->first();
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('emissora_processo_fase', 10, function () {
			return self::get();
		});
	}
	
	public function scopeWithTipoEndereco($query) {
		$query->leftJoin('emissora_tipo_endereco', 'emissora_tipo_endereco.tipo_enderecoID', 'emissora_endereco.tipo_enderecoID');
		return $query;
	}
	public function scopeWithEndereco($query) {
		$query->leftJoin('municipio', 'municipio.municipioID', 'emissora_endereco.municipioID')
			->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
		return $query;
	}
	
	public function scopeWithEmissora($query, $emissoraID) {
		return $query->where('emissoraID', $emissoraID);
	}
	
}
