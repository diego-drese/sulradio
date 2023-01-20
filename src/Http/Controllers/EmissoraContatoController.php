<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Contato;
use Oka6\SulRadio\Models\ContatoInfo;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Emissoracontato;
use Oka6\SulRadio\Models\EmissoraTipocontato;
use Oka6\SulRadio\Models\Funcao;
use Oka6\SulRadio\Models\UserSulRadio;
use Yajra\DataTables\DataTables;

class EmissoraContatoController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request, $clientId) {
		if ($request->ajax()) {
            $emissoraId = $request->get('emissora_id');
            $query = UserSulRadio::query()->where('active', '=', 1)->client($clientId);
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) use($emissoraId){
                    return route('client.user.edit', [ $row->client_id, $row->_id, 'emissora_id'=>$emissoraId]);
				})->addColumn('full_name', function ($row) {
					return $row->name.' '.$row->last_name;
				})->addColumn('function_name', function ($row) {
                    return $row->function_id ? Funcao::getById($row->function_id)->desc_funcao : '---';
                })->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.emissora-contato.index', ['clientId' => $clientId]);
	}

}