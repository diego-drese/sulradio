<?php
namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Oka6\SulRadio\Models\GhospWorklist;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Library\MongoUtils;
use Yajra\DataTables\DataTables;

class GhospController extends BaseController {
    public function index(Request $request) {
		
        if ($request->ajax()) {
	        $date           = $request->get('date');
	        $exame          = $request->get('exame');
	        $paciente       = $request->get('paciente');
	        $ghospWorklist  = GhospWorklist::query();
	        if($date){
		        list($dateStart, $dateEnd) = array_map('trim', explode('-', $date));
		        $dateStartObj   = new \DateTime();
		        $dateStart      = $dateStartObj->createFromFormat('d/m/Y H:i', $dateStart.' 00:00');
		        $dateEndObj     = new \DateTime();
		        $dateEnd        = $dateEndObj->createFromFormat('d/m/Y H:i', $dateEnd.' 23:59');
		        $ghospWorklist->where('exame_datahora', '>=', MongoUtils::convertDatePhpToMongo($dateStart))->where('exame_datahora', '<=', MongoUtils::convertDatePhpToMongo($dateEnd));
	        }
	        if($exame){
		        $ghospWorklist->where('exame_descricao', 'like', "%{$exame}%");
	        }
	        if($paciente){
		        $ghospWorklist->where('paciente_nome', 'like', "%{$paciente}%");
	        }
	        
	        
	        
	        
            return DataTables::of($ghospWorklist)
                ->addColumn('edit_url', function ($row) {
                    return route('qualita.ghosp.worklist.edit', [$row->id]);
                }) ->addColumn('exame_datahora', function ($row) {
		            return $row->exame_datahora->toDateTime()->format('d/m/Y H:i');
                })
                ->setRowClass(function () {
                    return 'center';
                })
                ->toJson(true);
        }

        $hasEdit = ResourceAdmin::hasResourceByRouteName('qualita.ghosp.worklist.edit', [1]);
        return view('SulRadio::backend.ghosp-worklist.index', compact( 'hasEdit'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Profile $profile) {
        $resources = Resource::all('name', 'id', 'route_name', 'menu','icon');
        $resourcesMenu = Resource::where('is_menu', 1)->where('can_be_default', 1)->get();
        $hasSave = ResourceAdmin::hasResourceByRouteName('admin.profiles.store');

        $oka6ProfileTypes = Config::get('admin.profile_type');
        return view('Admin::backend.profiles.create', compact('profile', 'resources', 'resourcesMenu', 'hasSave', 'oka6ProfileTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        $dataForm = $request->all();
        $this->validate($request, [
            'name' => 'required'
        ]);
        $dataForm['id'] = Sequence::getSequence(Profile::TABLE);
        $dataForm['resources_allow'] = array_map('intval', $dataForm['resources']);
        Profile::create($dataForm);

        toastr()->success('Profile Criado com sucesso', 'Sucesso');
        return redirect(route('admin.profiles.index'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $profile = Profile::getById((int)$id);
        $resources = Resource::all('name', 'id', 'route_name', 'menu','icon');
        $profilesResources = $profile->resources_allow;
        $resourcesMenu = Resource::where('is_menu', 1)->where('can_be_default', 1)->get();
        $hasSave = ResourceAdmin::hasResourceByRouteName('admin.profiles.update', [1]);
        $oka6ProfileTypes = Config::get('admin.profile_type');
        return view('Admin::backend.profiles.edit', compact('profile', 'resources', 'profilesResources', 'resourcesMenu', 'hasSave', 'oka6ProfileTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        if($id==User::PROFILE_ID_ROOT && !$request->get('resources')){
            toastr()->error("Não é permitido remover todos os recursos desse perfil", 'Erro');
            return redirect(route('admin.profiles.index'));
        }

        $profile = Profile::getById((int)$id);
        $dataForm = $request->all();
        $this->validate($request, [
            'name' => 'required'
        ]);
        $dataForm['resources_allow'] = array_map('intval', isset($dataForm['resources']) ? $dataForm['resources'] : []);
        $profile->fill($dataForm);
        $profile->save();

        toastr()->success("{$profile->name} Atualizado com sucesso", 'Sucesso');
        return redirect(route('admin.profiles.index'));
    }
}