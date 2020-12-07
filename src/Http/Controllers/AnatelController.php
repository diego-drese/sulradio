<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EstacaoRd;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\StatusSead;
use Oka6\SulRadio\Models\TipoEmissora;
use Oka6\SulRadio\Models\TipoRepresentanteSocial;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class AnatelController extends SulradioController {
	use ValidatesRequests;
	
	public function emissoras(Request $request) {
		
		if ($request->ajax()) {
			$date   = $request->get('date');
			$query  = EstacaoRd::query();
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
				//	return route('emissora.edit', [$row->emissoraID]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.estacao-rd.index', []);
		
	}
	
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
		];
		$this->parameters = $parameters;
	}
}