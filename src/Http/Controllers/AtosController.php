<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\StatusSead;
use Oka6\SulRadio\Models\TipoEmissora;
use Oka6\SulRadio\Models\TipoRepresentanteSocial;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class AtosController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$user = Auth::user();
			$date = $request->get('date');
			$query = Ato::query()
				->joinEmissora()
				->withTipo()
				->withCategoria()
				->withServico();
			
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
					return route('emissora.atos.oficiais.edit', [$row->emissoraID, $row->atoID]);
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.ato.index', []);
		
	}
	
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.edit', [1, 1]),
			'statusSead' => StatusSead::getWithCache(),
			'servico' => Servico::getWithCache(),
			'tipoEmissora' => TipoEmissora::getWithCache(),
			'tipoRepresetanteSocial' => TipoRepresentanteSocial::getWithCache(),
			'uf' => Uf::getWithCache(),
		];
		$this->parameters = $parameters;
	}
}