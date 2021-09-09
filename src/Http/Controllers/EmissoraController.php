<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\StatusSead;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketDocument;
use Oka6\SulRadio\Models\TipoEmissora;
use Oka6\SulRadio\Models\TipoRepresentanteSocial;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$user = Auth::user();
			$date = $request->get('date');
			$query = Emissora::query()
				->filterClient($user)
				->withStatusSead()
				->withClient()
				->withServico()
				->withLocalidade()
				->withUf();
			
			if ($date) {
				list($dateStart, $dateEnd) = array_map('trim', explode('-', $date));
				$dateStartObj = new \DateTime();
				$dateStart = $dateStartObj->createFromFormat('d/m/Y H:i', $dateStart . ' 00:00');
				$dateEndObj = new \DateTime();
				$dateEnd = $dateEndObj->createFromFormat('d/m/Y H:i', $dateEnd . ' 23:59');
				$query->where('exame_datahora', '>=', MongoUtils::convertDatePhpToMongo($dateStart))->where('exame_datahora', '<=', MongoUtils::convertDatePhpToMongo($dateEnd));
			}
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('emissora.edit', [$row->emissoraID]);
				})->addColumn('atos_oficiais', function ($row) {
					return route('emissora.atos.oficiais.index', [$row->emissoraID]);
				})->addColumn('atos_comerciais', function ($row) {
					return route('emissora.atos.comercial.index', [$row->emissoraID]);
				})->addColumn('processos', function ($row) {
					return route('emissora.processo.index', [$row->emissoraID]);
				})->addColumn('endereco', function ($row) {
					return route('emissora.endereco.index', [$row->emissoraID]);
				})->addColumn('contatos', function ($row) {
					return route('emissora.contato.index', [$row->emissoraID]);
				})->addColumn('socios', function ($row) {
					return route('emissora.socio.index', [$row->emissoraID]);
				})->addColumn('documents', function ($row) {
					return route('emissora.document.index', [$row->emissoraID]);
				})->addColumn('documents_legal', function ($row) {
					return route('emissora.document.legal.index', [$row->emissoraID]);
				})->addColumn('documents_engineering', function ($row) {
					return route('emissora.document.engineering.index', [$row->emissoraID]);
				})->addColumn('documents_admin', function ($row) {
					return route('emissora.document.admin.index', [$row->emissoraID]);
				})->addColumn('ticket', function ($row) {
					return route('emissora.tickets', [$row->emissoraID]);
				})->addColumn('client', function ($row) {
					if($row->client_id){
						return route('client.edit', [$row->client_id]);
					}
					return null;
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora.index', []);
		
	}

	public function tickets(Request $request, $emissoraID, $id=null) {

		if ($request->ajax()) {
			$user = Auth::user();
			$query = Ticket::query()
				->withSelectDataTable()
				->filterClient($user)
				->withStatus()
				->withPriority()
				->withCategory()
				->withEmissora()
				->withServico()
				->withLocalidade()
				->withUf()
				->where('ticket.emissora_id', $emissoraID)
				->where('show_client', 1);
			return DataTables::of($query)
				->addColumn('ticket_url', function ($row) use($emissoraID){
					return route('emissora.tickets', [$emissoraID, $row->id]);
				})
				->toJson(true);
		}


		$user       = Auth::user();
		$emissora = Emissora::getById($emissoraID, $user);
		if($id){
			$data       = Ticket::filterClient($user)
								->withSelectDataTable()
								->withStatus()
								->withPriority()
								->withCategory()
								->withEmissora()
								->withServico()
								->withLocalidade()
								->withUf()
								->where('ticket.emissora_id', $emissoraID)
								->where('ticket.id', $id)
								->where('show_client', 1)
								->first();
			$documents  = TicketDocument::getAllByTicketId($data->id);
			return $this->renderView('SulRadio::backend.emissora.ticket', ['data' => $data, 'user'=>$user, 'documents'=>$documents, 'emissora'=>$emissora]);
		}

		return $this->renderView('SulRadio::backend.emissora.tickets', ['emissoraID'=>$emissoraID, 'emissora'=>$emissora]);

	}
	
	public function create(Emissora $data) {
		return $this->renderView('SulRadio::backend.emissora.create', ['data' => $data]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'status_seadID' => 'required',
			'razao_social' => 'required',
			'servicoID' => 'required',
			'tipo_emissoraID' => 'required',
			'municipioID' => 'required',
		]);
		$create = Emissora::create($dataForm);
		toastr()->success('Emissora Criado com sucesso', 'Sucesso');
		return redirect(route('emissora.edit',[$create->emissoraID]));
		
	}
	
	public function edit($id) {
		$user = Auth::user();
		$data = Emissora::getById($id, $user);
		$data->ufID = null;
		$data->sedufID = null;
		$ufID = Municipio::getById($data->municipioID);
		$sedufID = Municipio::getById($data->localidade_sedeID);
		
		if ($ufID) {
			$data->ufID = $ufID->ufID;
		}
		if ($sedufID) {
			$data->sedufID = $sedufID->ufID;
		}
		
		
		return $this->renderView('SulRadio::backend.emissora.edit', ['data' => $data]);
	}
	
	public function update(Request $request, $id) {
		$user = Auth::user();
		$data = Emissora::getById($id, $user);
		$dataForm = $request->all();
		$this->validate($request, [
			'status_seadID' => 'required',
			'razao_social' => 'required',
			'servicoID' => 'required',
			'tipo_emissoraID' => 'required',
			'municipioID' => 'required',
		]);
		$data->fill($dataForm);
		$data->save();
		
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('emissora.index'));
	}
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasAdd' => ResourceAdmin::hasResourceByRouteName('emissora.create'),
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.edit', [1]),
			'hasStore' => ResourceAdmin::hasResourceByRouteName('emissora.store'),
			'hasUpdate' => ResourceAdmin::hasResourceByRouteName('emissora.update', [1]),
			'hasAtosOficiais' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.index', [1]),
			'hasAtosJunta' => ResourceAdmin::hasResourceByRouteName('emissora.atos.comercial.index', [1]),
			'hasProcessos' => ResourceAdmin::hasResourceByRouteName('emissora.processo.index', [1]),
			'hasSocios' => ResourceAdmin::hasResourceByRouteName('emissora.socio.index', [1]),
			'hasAddSocios' => ResourceAdmin::hasResourceByRouteName('emissora.socio.create', [1]),
			'hasContato' => ResourceAdmin::hasResourceByRouteName('emissora.contato.index', [1]),
			'hasAddContato' => ResourceAdmin::hasResourceByRouteName('emissora.contato.create', [1]),
			'hasEndereco' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.index', [1]),
			'hasAddEndereco' => ResourceAdmin::hasResourceByRouteName('emissora.endereco.create', [1]),
			'hasDocument' => ResourceAdmin::hasResourceByRouteName('emissora.document.index', [1]),
			'hasTicket' => ResourceAdmin::hasResourceByRouteName('emissora.tickets', [1]),
			'hasDocumentLegal' => ResourceAdmin::hasResourceByRouteName('emissora.document.legal.index', [1]),
			'hasDocumentEngineering' => ResourceAdmin::hasResourceByRouteName('emissora.document.engineering.index', [1]),
			'hasDocumentAdmin' => ResourceAdmin::hasResourceByRouteName('emissora.document.admin.index', [1]),
			'hasEditClient' => ResourceAdmin::hasResourceByRouteName('client.edit', [1]),
			
			'statusSead' => StatusSead::getWithCache(),
			'servico' => Servico::getWithCache(),
			'tipoEmissora' => TipoEmissora::getWithCache(),
			'tipoRepresetanteSocial' => TipoRepresentanteSocial::getWithCache(),
			'uf' => Uf::getWithCache(),
		];
		$this->parameters = $parameters;
	}
}