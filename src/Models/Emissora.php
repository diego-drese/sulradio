<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;

class Emissora extends Model {
    const TABLE = 'emissora';
    protected $fillable = [
	    'razao_social' ,
		'nome_fantasia' ,
		'municipioID' ,
		'servicoID' ,
		'clienteID' ,
		'frequencia' ,
		'classe' ,
		'potencia' ,
		'faixa_fronteira' ,
		'indicativo_chamada' ,
		'emissoraID' ,
		'tipo_repres_socialID' ,
		'tipo_nomeacaoID' ,
		'canal' ,
		'cnpj' ,
		'adm1' ,
		'fone_adm1' ,
		'fax_adm1' ,
		'email_adm1' ,
		'adm2' ,
		'fone_adm2' ,
		'fax_adm2' ,
		'email_adm2' ,
		'contato' ,
		'fone_contato' ,
		'fax_contato' ,
		'email_contato' ,
		'nire' ,
		'observacao' ,
		'endereco_sede',
		'bairro_sede',
		'localidade_sedeID' ,
		'cep_sede' ,
		'horario' ,
		'potencia_noturna' ,
		'celular_adm1' ,
		'celular_adm2' ,
		'celular_contato' ,
		'informativo',
		'inscricao_estadual' ,
		'ato_autorizativo' ,
		'tipo_emissoraID' ,
		'status_seadID' ,
		'logotipo',
		'mapa_cobertura' ,
		'mapa_localidade',
		'informacao_renovacao',
    ];
    protected $connection = 'sulradio';
    protected $table = 'emissora';
	public $timestamps = false;
	protected $primaryKey = 'emissoraID';
	
	public static function getById($id){
		return self::where('emissoraID', $id)
			->withLocalidade()
			->withStatusSead()
			->withServico()
			->withUf()
			->first();
	}
	
	public function scopeWithLocalidade($query){
		$query->leftJoin('municipio', 'municipio.municipioID', 'emissora.municipioID');
		return $query;
	}
	public function scopeWithServico($query){
		$query->leftJoin('servico', 'servico.servicoID', 'emissora.servicoID');
		return $query;
	}
	public function scopeWithStatusSead($query){
		$query->leftJoin('status_sead', 'status_sead.status_seadID', 'emissora.status_seadID');
		return $query;
	}
	public function scopeWithUf($query){
		$query->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
		return $query;
	}

}
