<?php

namespace Oka6\SulRadio\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Oka6\SulRadio\Helpers\Helper;

class EmissoraAtoJc extends Model {
	const TABLE = 'emissora_ato_jc';
	protected $fillable = [
		'ato_jcID',
		'emissoraID',
		'tipo_ato_juridicoID',
		'data_assinatura',
		'data_arquivo_junta',
		'arquivo_junta',
		'observacoes',
		'livro',
		'nire',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_ato_jc';
	protected $primaryKey = 'ato_jcID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('ato_jcID', $id)
			->withTipoAtoJuridico()
			->first();
	}
	
	public function getDataArquivoJuntaAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataArquivoJuntaAttribute($value) {
		$this->attributes['data_arquivo_junta'] = Helper::convertDateBrToMysql($value);
	}
	
	public function getDataAssinaturaAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataAssinaturaAttribute($value) {
		$this->attributes['data_assinatura'] = Helper::convertDateBrToMysql($value);
	}
	
	public function scopeWithTipoAtoJuridico($query) {
		$query->leftJoin('emissora_tipo_ato_juridico', 'emissora_tipo_ato_juridico.tipo_ato_juridicoID', 'emissora_ato_jc.tipo_ato_juridicoID');
		return $query;
	}
	
	public function scopeWithEmissora($query, $emissoraID) {
		return $query->where('emissoraID', $emissoraID);
	}
	
	public function scopeJoinEmissora($query) {
		return $query->join('emissora', 'emissora.emissoraID', 'ato.emissoraID')
			->leftJoin('municipio', 'municipio.municipioID', 'emissora.municipioID')
			->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
	}
}
