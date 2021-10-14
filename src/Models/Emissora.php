<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;use function GuzzleHttp\json_decode;

class Emissora extends Model {
	const TABLE = 'emissora';
	protected $fillable = [
		'razao_social',
		'nome_fantasia',
		'municipioID',
		'servicoID',
		'clienteID',
		'frequencia',
		'classe',
		'potencia',
		'faixa_fronteira',
		'indicativo_chamada',
		'emissoraID',
		'tipo_repres_socialID',
		'tipo_nomeacaoID',
		'canal',
		'cnpj',
		'adm1',
		'fone_adm1',
		'fax_adm1',
		'email_adm1',
		'adm2',
		'fone_adm2',
		'fax_adm2',
		'email_adm2',
		'contato',
		'fone_contato',
		'fax_contato',
		'email_contato',
		'nire',
		'observacao',
		'endereco_sede',
		'bairro_sede',
		'localidade_sedeID',
		'cep_sede',
		'horario',
		'potencia_noturna',
		'celular_adm1',
		'celular_adm2',
		'celular_contato',
		'informativo',
		'inscricao_estadual',
		'ato_autorizativo',
		'tipo_emissoraID',
		'status_seadID',
		'logotipo',
		'mapa_cobertura',
		'mapa_localidade',
		'informacao_renovacao',
	];
	protected $connection = 'sulradio';
	protected $table = 'emissora';
	protected $primaryKey = 'emissoraID';
	
	public function usesTimestamps() : bool{
		return false;
	}

	public static function getByIdOnly($id) {
		return self::where('emissoraID', $id)->first();
	}

	public static function getById($id, $user) {
		return self::where('emissoraID', $id)
			->filterClient($user)
			->withLocalidade()
			->withStatusSead()
			->withServico()
			->withUf()
			->first();
	}
	
	public function scopeWithLocalidade($query) {
		$query->leftJoin('municipio', 'municipio.municipioID', 'emissora.municipioID');
		return $query;
	}
	
	public function scopeWithServico($query) {
		$query->leftJoin('servico', 'servico.servicoID', 'emissora.servicoID');
		return $query;
	}

	public function scopeFilterClient($query, $user) {
		if($user->client_id){
			$client = Client::getById($user->client_id);
			$query->whereIn('emissoraID', json_decode($client->broadcast));
		}
		$userHasClient = UserHasClient::getEmissorasWithCache($user->id);

		if(count($userHasClient)){
			$query->whereIn('emissoraID', $userHasClient);
		}
		return $query;
	}
	
	public function scopeWithStatusSead($query) {
		$query->leftJoin('status_sead', 'status_sead.status_seadID', 'emissora.status_seadID');
		return $query;
	}
	public function scopeWithClient($query) {
		$query->leftJoin('client', 'client.id', 'emissora.client_id');
		return $query;
	}
	
	public function scopeWithUf($query) {
		$query->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
		return $query;
	}
	
	public static function updateClientId($clientId, $broadcast){
		self::where('client_id', $clientId)->update(['client_id'=>null]);
		self::whereIn('emissoraID', $broadcast)->whereNull('client_id')->update(['client_id'=>$clientId]);
	}
	
	public static function getByArrayId($broadcast){
		return self::whereIn('emissoraID', is_array($broadcast) ? $broadcast : [])
			->withLocalidade()
			->withServico()
			->withUf()
			->get();
	}
}
