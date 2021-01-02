<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\AtoCategoria;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Finalidade;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\ReferencialPenalidade;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\TipoAto;
use Oka6\SulRadio\Models\TipoPenalidade;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraAtosOficiaisController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $emissoraID) {
		
		if ($request->ajax()) {
			$query = Ato::query()
				->withEmissora($emissoraID)
				->withTipo()
				->withLocalidade()
				->withServico();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.atos.oficiais.edit', [$row->emissoraID, $row->atoID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-ato.index', ['emissoraID' => $emissoraID]);
	}
	
	public function create(Ato $data, $emissoraID) {
		return $this->renderView('SulRadio::backend.emissora-ato.create', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_atoID' => 'required',
			'data_dou' => 'required',
			'conferido' => 'required',
			'categoriaID' => 'required',
			'servicoID' => 'required',
			'finalidadeID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		$dataForm['data_ato'] = Helper::convertDateBrToMysql($dataForm['data_ato']);
		$dataForm['data_dou'] = Helper::convertDateBrToMysql($dataForm['data_dou']);
		$dataForm['data_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_renovacao']);
		$dataForm['data_ato_outorga'] = Helper::convertDateBrToMysql($dataForm['data_ato_outorga']);
		$dataForm['data_dou_outorga'] = Helper::convertDateBrToMysql($dataForm['data_dou_outorga']);
		$dataForm['data_ato_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_ato_renovacao']);
		$dataForm['data_dou_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_dou_renovacao']);
		$dataForm['data_inicio_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_inicio_renovacao']);
		Ato::create($dataForm);
		toastr()->success('Ato Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.atos.oficiais.index', $emissoraID));
		
	}
	
	public function edit($emissoraID, $id) {
		$data = Ato::getById($id);
		$data->ufID = null;
		$ufID = Municipio::getById($data->municipioID);
		if ($ufID) {
			$data->ufID = $ufID->ufID;
		}
		return $this->renderView('SulRadio::backend.emissora-ato.edit', ['data' => $data, 'emissoraID' => $emissoraID]);
	}
	
	public function update(Request $request, $emissoraID, $id) {
		$data = Ato::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'tipo_atoID' => 'required',
			'data_dou' => 'required',
			'conferido' => 'required',
			'categoriaID' => 'required',
			'servicoID' => 'required',
			'finalidadeID' => 'required',
		]);
		
		$dataForm['data_ato'] = Helper::convertDateBrToMysql($dataForm['data_ato']);
		$dataForm['data_dou'] = Helper::convertDateBrToMysql($dataForm['data_dou']);
		$dataForm['data_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_renovacao']);
		$dataForm['data_ato_outorga'] = Helper::convertDateBrToMysql($dataForm['data_ato_outorga']);
		$dataForm['data_dou_outorga'] = Helper::convertDateBrToMysql($dataForm['data_dou_outorga']);
		$dataForm['data_ato_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_ato_renovacao']);
		$dataForm['data_dou_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_dou_renovacao']);
		$dataForm['data_inicio_renovacao'] = Helper::convertDateBrToMysql($dataForm['data_inicio_renovacao']);
		
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.atos.oficiais.index', [$emissoraID]));
	}
	
	protected function makeParameters($extraParameter = null) {
		$user = Auth::user();
		$emissora = Emissora::getById($extraParameter['emissoraID'], $user);
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.create', [1]),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.edit', [1, 1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.store', [1]),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.update', [1, 1]),
			'tipoAto' => TipoAto::getWithCache(),
			'uf' => Uf::getWithCache(),
			'client' => Client::getById($emissora->client_id),
			'categoria' => AtoCategoria::getWithCache(),
			'servico' => Servico::getWithCache(),
			'finalidade' => Finalidade::getWithCache(),
			'tipoPenalidade' => TipoPenalidade::getWithCache(),
			'referenciaPenalidade' => ReferencialPenalidade::getWithCache(),
			'emissora' => $emissora,
		
		];
		$this->parameters = $parameters;
	}
}