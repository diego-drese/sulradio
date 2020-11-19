<?php
namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Municipio;
use Oka6\SulRadio\Models\Servico;
use Oka6\SulRadio\Models\StatusSead;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\TipoEmissora;
use Oka6\SulRadio\Models\TipoRepresentanteSocial;
use Oka6\SulRadio\Models\Uf;
use Yajra\DataTables\DataTables;

class EmissoraController extends SulradioController {
	use ValidatesRequests;
	protected function makeParameters($extraParameter=null){
		$parameters = [
			'hasAdd'                => ResourceAdmin::hasResourceByRouteName('emissora.create'),
			'hasEdit'               => ResourceAdmin::hasResourceByRouteName('emissora.edit', [1]),
			'hasStore'              => ResourceAdmin::hasResourceByRouteName('emissora.store'),
			'hasUpdate'             => ResourceAdmin::hasResourceByRouteName('emissora.update', [1]),
			'statusSead'            => StatusSead::getWithCache(),
			'servico'               => Servico::getWithCache(),
			'tipoEmissora'          => TipoEmissora::getWithCache(),
			'tipoRepresetanteSocial'=> TipoRepresentanteSocial::getWithCache(),
			'uf'                    => Uf::getWithCache(),
		];
		$this->parameters = $parameters;
	}
    public function index(Request $request) {
	    
        if ($request->ajax()) {
	        $date           = $request->get('date');
	        $exame          = $request->get('exame');
	        $paciente       = $request->get('paciente');
	        $query          = Emissora::query()
		        ->withStatusSead()
		        ->withServico()
		        ->withLocalidade();
	        
	        if($date){
		        list($dateStart, $dateEnd) = array_map('trim', explode('-', $date));
		        $dateStartObj   = new \DateTime();
		        $dateStart      = $dateStartObj->createFromFormat('d/m/Y H:i', $dateStart.' 00:00');
		        $dateEndObj     = new \DateTime();
		        $dateEnd        = $dateEndObj->createFromFormat('d/m/Y H:i', $dateEnd.' 23:59');
		        $query->where('exame_datahora', '>=', MongoUtils::convertDatePhpToMongo($dateStart))->where('exame_datahora', '<=', MongoUtils::convertDatePhpToMongo($dateEnd));
	        }
            return DataTables::of($query)
                ->addColumn('edit_url', function ($row) {
                    return route('emissora.edit', [$row->emissoraID]);
                })->addColumn('atos_oficiais', function ($row) {
                    return route('emissora.atos.oficiais.index', [$row->emissoraID]);
                })->setRowClass(function () {
                    return 'center';
                })
                ->toJson(true);
        }
        return $this->renderView('SulRadio::backend.emissora.index', []);

    }


  
    public function create(Emissora $data) {
	    return $this->renderView('SulRadio::backend.emissora.create', ['data'=>$data]);
    }

    public function store(Request $request) {
        $dataForm = $request->all();
        $this->validate($request, [
            'status_seadID'     => 'required',
            'razao_social'      => 'required',
            'servicoID'         => 'required',
            'tipo_emissoraID'   => 'required',
            'municipioID'       => 'required',
        ]);
        Emissora::create($dataForm);
        toastr()->success('Emissora Criado com sucesso', 'Sucesso');
        return redirect(route('emissora.index'));

    }


 
    public function edit($id) {
	    $data           = Emissora::getById($id);
	    $data->ufID     = null;
	    $data->sedufID  = null;
	    $ufID           = Municipio::getById($data->municipioID);
	    $sedufID        = Municipio::getById($data->localidade_sedeID);
	    
	    if($ufID){
		    $data->ufID = $ufID->ufID;
	    }
	    if($sedufID){
		    $data->sedufID = $sedufID->ufID;
	    }
	    
	    
	    return $this->renderView('SulRadio::backend.emissora.edit', ['data'=>$data]);
    }

   
    public function update(Request $request, $id) {
        $data = Emissora::getById($id);
        $dataForm = $request->all();
	    $this->validate($request, [
		    'status_seadID'     => 'required',
		    'razao_social'      => 'required',
		    'servicoID'         => 'required',
		    'tipo_emissoraID'   => 'required',
		    'municipioID'       => 'required',
	    ]);
	    $data->fill($dataForm);
	    $data->save();

        toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
        return redirect(route('emissora.index'));
    }
}