<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\AtoCategoria;
use Oka6\SulRadio\Models\AtoNotification;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Dou;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Finalidade;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\ReferencialPenalidade;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\TipoAto;
use Oka6\SulRadio\Models\TipoPenalidade;
use Oka6\SulRadio\Models\Uf;
use Oka6\SulRadio\Models\UserSulRadio;
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

	public function create(Request $request, Ato $data, $emissoraID) {
		$douId              = $request->get('dou_id');
		$user               = Auth::user();
		$emissora           = Emissora::getById($emissoraID, $user);
		$data->servicoID    = $emissora->servicoID;
		$data->canal_freq   = $emissora->canal.'/'.$emissora->frequencia;
		$data->classe       = $emissora->classe;
        //$usersClients       =  UserSulRadio::query()->client($emissora->client_id)->get();
        $usersClients       =  Client::getUsersByEmissora($emissoraID);
		if($douId){
			$dou                = Dou::getById($douId);
			$tipo               = TipoAto::getOrCreateByName($dou->type_name);
			$data->tipo_atoID   = $tipo->tipo_atoID;
			$data->numero_ato   = $dou->id;
			$data->data_dou     = $dou->date->format('Y-m-d H:i:s');
			$data->secao        = $dou->pub_name;
			$data->pagina       = $dou->page_number;
			$data->ato_url      = $dou->url_dou;
			$text               = str_replace('>','> ',$dou->text);
			$data->observacao   = strip_tags($text);

		}
		$data->ufID = null;
		$ufID       = Municipio::getById($emissora->municipioID);
		if ($ufID) {
			$data->municipioID = $ufID->municipioID;
			$data->ufID = $ufID->ufID;
		}
		return $this->renderView('SulRadio::backend.emissora-ato.create', ['data' => $data, 'emissoraID' => $emissoraID, 'douId'=>$douId, 'usersClients'=>$usersClients]);
	}

	public function store(Request $request, $emissoraID) {
		$dataForm = $request->all();
		$user       = Auth::user();
		$this->validate($request, [
			'tipo_atoID' => 'required',
			'data_dou' => 'required',
			'conferido' => 'required',
			'categoriaID' => 'required',
			'servicoID' => 'required',
			'finalidadeID' => 'required',
		]);
		$dataForm['emissoraID'] = $emissoraID;
		$dataForm['data_ato']   = Helper::convertDateBrToMysql($dataForm['data_ato']);
		$dataForm['data_dou']   = Helper::convertDateBrToMysql($dataForm['data_dou']);
		$ato                    = Ato::create($dataForm);
        $emissora               = Emissora::getById($emissoraID, $user);
		if(isset($dataForm['douId']) && !empty($dataForm['douId'])){
			$dou                = Dou::getById($dataForm['douId']);
			if(!$dou->emissora_id){
				$dou->ato_id        = $ato->atoID;
				$dou->emissora_id   = $emissoraID;
				$dou->emissora_name = $emissora->razao_social;
				$dou->save();
			}
		}
        AtoNotification::add($emissora, $ato, $user);
		toastr()->success('Ato Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.atos.oficiais.index', $emissoraID));

	}

	public function edit($emissoraID, $id) {
		$data = Ato::getById($id);
		$data->ufID = null;
		$ufID = Municipio::getById($data->municipioID);
        $usersClients= AtoNotification::getUsersByAtoId($id);
		if ($ufID) {
			$data->ufID = $ufID->ufID;
		}
		return $this->renderView('SulRadio::backend.emissora-ato.edit', ['data' => $data, 'emissoraID' => $emissoraID, 'usersClients'=>$usersClients]);
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
