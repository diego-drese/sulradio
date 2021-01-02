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
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Plan;
use Yajra\DataTables\DataTables;

class MetricsController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		$totalClients       = Helper::formatInteger(Client::count());
		$totalBroadCast     =  Helper::formatInteger(Emissora::count());
		$totalDocuments     =  Helper::formatInteger(Document::currentVersion()->count());
		$totalAtos          =  Helper::formatInteger(Ato::count());
		return $this->renderView('SulRadio::backend.metrics.index', ['totalClients' => $totalClients, 'totalBroadCast'=>$totalBroadCast, 'totalDocuments'=>$totalDocuments, 'totalAtos'=>$totalAtos]);
	}
	
	public function broadcast(Request $request) {
		$date       = explode(' - ', $request->get('date'));
		$dateStart  = new \DateTime(Helper::convertDateBrToMysql($date[0]));
		$dateStart->setTime('0', '0', '0');
		$dateEnd    = new \DateTime(Helper::convertDateBrToMysql($date[1]));
		$dateEnd->setTime('23', '59','59');
		
		return response()->json([$dateStart, $dateEnd]);
	}
	
	public function documentAction(Request $request) {
		$date       = explode(' - ', $request->get('date'));
		$dateStart  = new \DateTime(Helper::convertDateBrToMysql($date[0]));
		$dateStart->setTime('0', '0', '0');
		$dateEnd    = new \DateTime(Helper::convertDateBrToMysql($date[1]));
		$dateEnd->setTime('23', '59','59');
		$documents = DocumentHistoric::selectRaw('count(1) as total, action')
			->where('created_at', '>=', $dateStart)
			->where('created_at', '<=', $dateEnd)
			->groupBy('action')
			->get();
		foreach ($documents as &$document){
			$document->name = DocumentHistoric::ACTION_TRANSLATE[$document->action];
		}
		return response()->json(['data'=>$documents, 'result'=>true]);
	}
	public function documentNew(Request $request) {
		$date       = explode(' - ', $request->get('date'));
		$dateStart  = new \DateTime(Helper::convertDateBrToMysql($date[0]));
		$dateStart->setTime('0', '0', '0');
		$dateEnd    = new \DateTime(Helper::convertDateBrToMysql($date[1]));
		$dateEnd->setTime('23', '59','59');
		$documents = Document::selectRaw('count(1) as total, document_type.name as name')
			->join('document_type', 'document_type.id', 'document.document_type_id')
			->where('document.created_at', '>=', $dateStart)
			->where('document.created_at', '<=', $dateEnd)
			->groupBy('name')
			->get();
		
		return response()->json(['data'=>$documents, 'result'=>true]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'max_upload' => 'required',
			'max_broadcast' => 'required',
			'max_user' => 'required',
			'value' => 'required',
			'frequency' => 'required',
			'is_active' => 'required',
		]);
		Plan::create($dataForm);
		toastr()->success('Plano Criado com sucesso', 'Sucesso');
		return redirect(route('plan.index'));
		
	}
	
	public function edit($id) {
		$data = Plan::getById($id);
		return $this->renderView('SulRadio::backend.plan.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$data = Plan::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'max_upload' => 'required',
			'max_broadcast' => 'required',
			'max_user' => 'required',
			'value' => 'required',
			'frequency' => 'required',
			'is_active' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('plan.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('plan.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('plan.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('plan.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('plan.update', [1])
		];
		$this->parameters = $parameters;
	}
}