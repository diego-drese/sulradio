<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\SulRadio\Models\Cities;
use Oka6\SulRadio\Models\EstacaoRd;
use Oka6\SulRadio\Models\States;

class PublicController extends SulradioController {
	use ValidatesRequests;
	public function searchCity(Request $request) {
		$search = $request->get('search');
		$query = Cities::where('title', 'like', $search.'%')
			->limit(10)
			->get();
		foreach ($query as &$city){
			$state = States::getByIdWithCache($city->state_id);
			$city->state_name = $state->title;
			$city->state_letter = $state->letter;
			$city->text = $city->title." ({$state->letter})";
		}
		return response()->json($query, 200);
	}
	public function searchBroadcast(Request $request) {
		$search = $request->get('search');
		$clientID = $request->get('client_id');
		$query  = EstacaoRd::where('entidade.entidade_nome_entidade', 'like', '%'.$search.'%')
			->limit(10)
			->get();
		foreach ($query as &$broadcast){
			$broadcast->disabled = false;
			$broadcast->name = $broadcast->entidade['entidade_nome_entidade'];
			$broadcast->text = $broadcast->servico.'-'.$broadcast->name."({$broadcast->municipio})";
			if($broadcast->client_id && $broadcast->client_id!=$clientID){
				$broadcast->disabled = true;
			}
			
		}
		return response()->json($query, 200);
	}
	
}