<?php

namespace Oka6\SulRadio\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ato extends Model {
	const TABLE = 'ato';
	protected $fillable = [
		'atoID',
		'tipo_atoID',
		'numero_ato',
		'data_ato',
		'data_dou',
		'secao',
		'pagina',
		'processo',
		'empresa',
		'municipioID',
		'servicoID',
		'finalidadeID',
		'canal_freq',
		'classe',
		'ato_ref',
		'tipo_ato_ref',
		'observacao',
		'data_alteracao',
		'categoriaID',
		'historicoID',
		'conferido',
		'data_renovacao',
		'concorrenciaID',
		'emissoraID',
		'ato_outorgaID',
		'tipo_ato_outorga',
		'data_ato_outorga',
		'data_dou_outorga',
		'ato_renovacao',
		'data_ato_renovacao',
		'tipo_ato_renovacao',
		'data_dou_renovacao',
		'processo_renovacao',
		'data_inicio_renovacao',
		'tipo_penalidadeID',
		'valor_penalidade',
		'referencia_penalidadeID',
	];
	protected $connection = 'sulradio';
	protected $table = 'ato';
	protected $primaryKey = 'atoID';
	public function usesTimestamps() : bool{
		return false;
	}
	public static function getById($id) {
		return self::where('atoID', $id)
			->first();
	}
	
	public function getDataAtoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataDouAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataRenovacaoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataAtoOutorgaAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataDouOutorgaAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataAtoRenovacaoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataDouRenovacaoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function getDataInicioRenovacaoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function scopeWithLocalidade($query) {
		return $query->leftJoin('municipio', 'municipio.municipioID', 'ato.municipioID');
	}
	public function scopeJoinEmissora($query) {
		return $query->join('emissora', 'emissora.emissoraID', 'ato.emissoraID')
			->leftJoin('municipio', 'municipio.municipioID', 'emissora.municipioID')
			->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
	}
	public function scopeWithEmissora($query, $emissoraID) {
		return $query->where('emissoraID', $emissoraID);
	}
	
	public function scopeWithTipo($query) {
		return $query->leftJoin('tipo_ato', 'tipo_ato.tipo_atoID', 'ato.tipo_atoID');
	}
	
	public function scopeWithServico($query) {
		return $query->leftJoin('servico', 'servico.servicoID', 'ato.servicoID');
	}
	
	public function scopeWithCategoria($query) {
		return $query->leftJoin('ato_categoria', 'ato_categoria.categoriaID', 'ato.categoriaID');
	}
	
	
}
