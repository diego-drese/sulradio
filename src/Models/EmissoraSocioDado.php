<?php

namespace Oka6\SulRadio\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\SulRadio\Helpers\Helper;

class EmissoraSocioDado extends Model {
	const TABLE = 'emissora_socio_dado';
	public $timestamps = false;
	protected $fillable = [
		'socioID',
		'emissoraID',
		'cpf',
		'cotas',
		'valor_cotas',
		'percentual',
		'categoria_socioID',
		'fone',
		'fax',
		'email',
		'logradouro',
		'numero',
		'complemento',
		'bairro',
		'municipioID',
		'celular',
		'cep',
		'identidade',
		'profissao',
		'orgao_exp',
		'data_expedicao',
		'estado_civilID',
		'data_ingresso',
		'tipo_ingressoID',
		'moeda_cotas',
		'nacionalidade',
		'onominativa',
		'pnominativa',
		'cargoID',
		'numero_alteracao',
		'tipo_mandato',
		'observacoes',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora_socio_dado';
	protected $primaryKey = 'socioID';
	
	public static function getById($id) {
		return self::where('socioID', $id)->first();
	}
	
	public function scopeWithCategoria($query) {
		$query->leftJoin('emissora_socio_categoria', 'emissora_socio_categoria.categoriaID', 'emissora_socio_dado.categoria_socioID');
		return $query;
	}
	public function scopeWithCargo($query) {
		$query->leftJoin('emissora_socio_cargo', 'emissora_socio_cargo.cargoID', 'emissora_socio_dado.cargoID');
		return $query;
	}
	
	public function scopeWithEstadoCivil($query) {
		$query->leftJoin('estado_civil', 'estado_civil.estado_civilID', 'emissora_socio_dado.estado_civilID');
		return $query;
	}
	
	public function scopeWithTipoIngresso($query) {
		$query->leftJoin('emissora_socio_tipo_ingresso', 'emissora_socio_tipo_ingresso.tipo_ingressoID', 'emissora_socio_dado.tipo_ingressoID');
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
	
	public function getDataExpedicaoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataExpedicaoAttribute($value) {
		$this->attributes['data_expedicao'] = Helper::convertDateBrToMysql($value);
	}
	
	public function getDataIngressoAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setDataIngressoAttribute($value) {
		$this->attributes['data_ingresso'] = Helper::convertDateBrToMysql($value);
	}
	
}
