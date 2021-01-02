<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class EstacaoRd extends Model {
	
	const TABLE = 'estacao_rd';
	protected $fillable = [
		'checksum',
		'item',
		'siglaservico',
		'state',
		'entidade',
		'fistel',
		'cnpj',
		'codmunicipio',
		'municipio',
		'uf',
		'administrativo',
		'enderecos',
		'estacao_principal',
		'estacao_auxiliar',
		'transmissor_auxiliar',
		'transmissor_auxiliar2',
		'linha_auxiliar',
		'antena_auxiliar',
		'horario_funcionamento',
		"item",
		"idtplanobasico",
		"pais",
		"uf",
		"codmunicipio",
		"municipio",
		"frequencia",
		"classe",
		"servico",
		"status",
		"entidade",
		"cnpj",
		"latitudegms",
		"longitudegms",
		"latitude",
		"longitude",
		"erp_dia",
		"numtorres_diurno",
		"altura_diurno",
		"k_diurno",
		"psi_diurno",
		"espaçamento_diurno",
		"azimute_diurno",
		"erp_noturno",
		"numtorres_noturno",
		"altura_noturno",
		"k_noturno",
		"psi_noturno",
		"espacamento_noturno",
		"azimute_noturno",
		"campocaracteristico",
		"observacoes",
		"fistel",
		"client_id",
	];
	protected $table = 'estacao_rd';
	protected $connection = 'sulradio_mongo';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getByArrayId($broadcast){
		return self::whereIn('id', is_array($broadcast) ? $broadcast : [])->get();
	}
	
}
