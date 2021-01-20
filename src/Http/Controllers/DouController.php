<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Document;
use Oka6\SulRadio\Models\DocumentHistoric;
use Oka6\SulRadio\Models\Dou;
use Oka6\SulRadio\Models\DouCategory;
use Oka6\SulRadio\Models\DouType;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Plan;
use Yajra\DataTables\DataTables;

class DouController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		$totalClients       =  Helper::formatInteger(Client::count());
		$totalBroadCast     =  Helper::formatInteger(Emissora::count());
		$totalDocuments     =  Helper::formatInteger(Document::currentVersion()->count());
		$totalAtos          =  Helper::formatInteger(Ato::count());
		if ($request->ajax()) {
			
			$searchCategory = $request->get('search_category');
			if($searchCategory){
				$search = DouCategory::searchCategory($searchCategory);
				return response()->json($search);
			}
			$searchType     = $request->get('search_type');
			if($searchType){
				$search = DouType::searchCategory($searchType);
				return response()->json($search);
			}
			
			$searchEmissora     = $request->get('search_emissora');
			if($searchEmissora){
				$search = Emissora::get;
				return response()->json($search);
			}
			$id     = $request->get('id');
			if($id){
				$search = Dou::getById($id);
				return response()->json($search);
			}
			
			$query          = Dou::query();
			$order          = $request->get('my_order');
			$direction      = $request->get('direction');
			$period         = $request->get('period');
			if($period){
				$query->withPeriod($period);
			}
			$categories     = $request->get('categories');
			if($categories){
				$query->withCategories($categories);
			}
			$types          = $request->get('types');
			if($types){
				$query->withTypes($types);
			}
			$subject        = $request->get('subject');
			if($subject){
				$query->withSubject($subject);
			}
			$pub            = $request->get('pub');
			if($pub){
				$query->withPub($pub);
			}
			if($order && $direction){
				$query->orderBy($order, $direction);
			}else{
				$query->orderBy('date', 'desc');
			}
		
			return DataTables::of($query)->toJson(true);
			/** Busca os artigos */
		}
		return $this->renderView('SulRadio::backend.dou.index', ['totalClients' => $totalClients, 'totalBroadCast'=>$totalBroadCast, 'totalDocuments'=>$totalDocuments, 'totalAtos'=>$totalAtos]);
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAddAto' => ResourceAdmin::hasResourceByRouteName('plan.store'),
			'hasEditAto' => ResourceAdmin::hasResourceByRouteName('plan.store'),
		];
		$this->parameters = $parameters;
	}
}