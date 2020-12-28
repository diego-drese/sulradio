<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Oka6\SulRadio\Helpers\Helper;

class EmissoraProcesso extends Model {
	const TABLE = 'emissora_processo';
	protected $fillable = [
		'processoID',
		'emissoraID',
		'sicap',
		'data_protocolo',
		'data_ult_mov',
		'assunto',
		'ultima_mov',
		'observacao',
		'processo_faseID',
		'processo_vinculo',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_processo';
	protected $primaryKey = 'processoID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('processoID', $id)
			->first();
	}
	
	public function getDataProtocoloAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataProtocoloAttribute($value) {
		$this->attributes['data_protocolo'] = Helper::convertDateBrToMysql($value);
	}
	
	public function getDataUltMovAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataUltMovAttribute($value) {
		$this->attributes['data_ult_mov'] = Helper::convertDateBrToMysql($value);
	}
	
	
	public function scopeWithProcessoFase($query) {
		return $query->leftJoin('emissora_processo_fase', 'emissora_processo_fase.processo_faseID', 'emissora_processo.processo_faseID');
	}
	
	public function scopeWithEmissora($query, $emissoraID) {
		return $query->where('emissoraID', $emissoraID);
	}
	
	
}
