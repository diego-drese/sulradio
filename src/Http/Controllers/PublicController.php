<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Oka6\SulRadio\Models\Cities;
use Oka6\SulRadio\Models\Document;
use Oka6\SulRadio\Models\DocumentHistoric;
use Oka6\SulRadio\Models\Emissora;
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
		$query  = Emissora::where('razao_social', 'like', '%'.$search.'%')
			->withLocalidade()
			->withServico()
			->withUf()
			->limit(10)
			->get();
		foreach ($query as &$broadcast){
			$broadcast->disabled = false;
			$broadcast->id      = $broadcast->emissoraID;
			$broadcast->name    = $broadcast->nome_fantasia;
			$broadcast->text    = $broadcast->desc_servico.'-'.$broadcast->razao_social."({$broadcast->desc_municipio} {$broadcast->desc_uf})";
			if($broadcast->client_id!=null && $broadcast->client_id!=$clientID){
				$broadcast->disabled = true;
			}
		}
		return response()->json($query, 200);
	}
	
	public function downloadDocument($id){
		$user = Auth::user();
		$document = Document::getById($id, $user);
		if(!$document){
			return redirect(route('admin.page404get'));
		}
		
		$urlTemp = Storage::disk('spaces')->temporaryUrl($document->file_name, now()->addMinutes(5));
		DocumentHistoric::create([
			'user_id'=> $user->id,
			'document_id'=> $document->id,
			'action'=> DocumentHistoric::ACTION_DOWNLOADED,
		]);
		return redirect($urlTemp);
	}
	
}