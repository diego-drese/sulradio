<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Library\MongoUtils;
use Oka6\Admin\Models\PasswordReset;
use Oka6\Admin\Models\Profile;
use Oka6\Admin\Models\Sequence;
use Oka6\Admin\Models\User;
use Oka6\Admin\Notifications\ResetPasswordNotification;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\Funcao;
use Oka6\SulRadio\Models\Plan;
use Oka6\SulRadio\Models\UserSulRadio;
use Yajra\DataTables\DataTables;

class ClientController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$query = Client::query();
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) {
					return route('client.edit', [$row->id]);
				})->addColumn('plan_name', function ($row) {
					return Plan::getById($row->plan_id)->name;
				})->addColumn('users_url', function ($row) {
					return route('client.user', [$row->id]);
				})->addColumn('broadcast_url', function ($row) {
					return route('client.broadcast', [$row->id]);
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.client.index', []);
	}
	
	public function create(Client $data) {
		return $this->renderView('SulRadio::backend.client.create', ['data' => $data, 'broadcast'=>[]]);
	}
	
	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'plan_id' => 'required',
			'is_active' => 'required',
			'broadcast' => 'required',
		]);
		if($request->get('city_id')){
//			$city   = Cities::getById($request->get('city_id'));
//			$state  = States::getByIdWithCache($city->state_id);
//			$dataForm['city_name'] = $city->title." ({$state->letter})";
		}
		$brodcast                   = $dataForm['broadcast'];
		$dataForm['users']          = json_encode([]);
		$dataForm['uploads_gb']     = 0;
		$dataForm['broadcast']      = json_encode($brodcast);
		$dataForm['validated_at']   = date('Y-m-d H:i:s');
		$client = Client::create($dataForm);
		Emissora::updateClientId($client->id , $brodcast);
		toastr()->success('Cliente criado com sucesso', 'Sucesso');
		return redirect(route('client.index'));
		
	}
	
	public function edit($id) {
		$data = Client::getById($id);
		return $this->renderView('SulRadio::backend.client.edit', ['data' => $data, 'broadcast'=> Emissora::getByArrayId(json_decode($data->broadcast))]);
	}
	
	public function update(Request $request, $id) {
		$data = Client::getById($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'plan_id' => 'required',
			'is_active' => 'required',
			'broadcast' => 'required',
		]);
		$brodcast                   = $dataForm['broadcast'];
		$dataForm['broadcast']      = json_encode($brodcast);
		$data->fill($dataForm);
		$data->save();
		Emissora::updateClientId($id , $brodcast);
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('client.index'));
	}
	public function user(Request $request, $clientId) {
		if ($request->ajax()) {
			$query = UserSulRadio::query()->client($clientId);
			return DataTables::of($query)
				->addColumn('edit_url', function ($row) use($clientId) {
					return route('client.user.edit', [$clientId, $row->_id]);
				})->addColumn('notify_url', function ($row) use($clientId) {
					return route('client.user.notify', [$clientId, $row->id]);
				})->addColumn('profile_name', function ($row) {
					$profile = Profile::getById($row->profile_id);
					return $profile->name;
				})->addColumn('last_login_at', function ($row) {
					return $row->last_login_at;
				})->addColumn('function_name', function ($row) {
					return $row->function_id ? Funcao::getById($row->function_id)->desc_funcao : '---';
				})->addColumn('last_notification_at', function ($row) {
					return $row->last_notification_at;
				})->toJson(true);
		}
		return $this->renderView('SulRadio::backend.client.user.index', ['client'=>Client::getById($clientId)]);
	}
	public function userCreate(UserSulRadio $data, $clientId) {
		return $this->renderView('SulRadio::backend.client.user.create', ['data' => $data, 'client'=>Client::getById($clientId), 'profiles' => Profile::getProfilesByTypes(Config::get('sulradio.profile_type'))]);
	}
	public function userStore(Request $request, $clientId) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'profile_id' => 'required|integer',
			'email' => ['required', function ($attribute, $value, $fail) {
				$result = User::where('email', $value)->first();
				if ($result) {
					$fail($attribute . ' já cadastrado.');
				}
			},]
		
		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);

		$user = Auth::user();
		$dataForm['id']                     = Sequence::getSequence('users');
		$dataForm['client_id']              = (int)$clientId;
		$dataForm['active']                 = isset($dataForm['active']) && $dataForm['active'] ? 1 : 0;
		$dataForm['receive_notification']   = isset($dataForm['receive_notification']) && $dataForm['receive_notification'] ? 1 : 0;
		$dataForm['password']               = bcrypt(Str::random(10));
		$dataForm['remember_token']         = Str::random(60);
		$dataForm['user_created_id']        = (int)$user->id;
		$dataForm['user_updated_id']        = (int)$user->id;
		$dataForm['user_updated_at']        = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
		UserSulRadio::create($dataForm);
		$this->notifyUser($clientId, $dataForm['id']);
		toastr()->success('Usuário criado com sucesso', 'Sucesso');
		return redirect(route('client.user', [$clientId]));
	}
	
	public function notifyUser($clientId, $idUser){
		$user = UserSulRadio::where('id', (int)$idUser)->first();
		if($user->last_notification_at!='---'){
			$now = new \DateTime();
			$lastNotified = MongoUtils::convertDateMongoToPhpDateTime($user->getAttributes()['last_notification_at']);
			$diff = $now->diff($lastNotified);
			if($diff->i<10){
				toastr()->error('Aguarde no minimo 10 minutos para reenviar o email', 'Atenção');
				return redirect(route('client.user', [$clientId]));
			}
		}
		if(!$user->remember_token){
			$user->remember_token = Str::random(60);
			$user->save();
		}
		if($user->last_login_at=='---'){
			$passwordReset = PasswordReset::create(['email' => $user->email, 'token' => $user->remember_token, 'new_account' => true]);
		}else{
			$passwordReset = PasswordReset::create(['email' => $user->email, 'token' => $user->remember_token, 'new_account' => false]);
		}
		
		try {
			$user->notify(new ResetPasswordNotification($passwordReset));
			$user->last_notification_at = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
			$user->save();
			toastr()->success('Email enviado com sucesso', 'Sucesso');
		}catch (\Exception $e){
			toastr()->error('Não foi possivel enviar o email '.$e->getMessage().'', 'Atenção');
		}
		return redirect(route('client.user', [$clientId]));
	}
	
	public function userEdit(Request $request, $clientId, $userId) {
		$data = UserSulRadio::getBy_Id($userId);
        $emissoraId = $request->get('emissora_id');
		return $this->renderView('SulRadio::backend.client.user.edit', ['emissoraId'=>$emissoraId, 'data' => $data, 'client'=>Client::getById($clientId), 'profiles' => Profile::getProfilesByTypes(Config::get('sulradio.profile_type'))]);
	}
	public function userUpdate(Request $request, $clientId, $userId) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'profile_id' => 'required|integer',
			'email' => ['required', function ($attribute, $value, $fail) use($userId) {
				$result = User::where('email', $value)->where('_id', '!=', new \MongoDB\BSON\ObjectId($userId))->first();
				if ($result) {
					$fail($attribute . ' já cadastrado.');
				}
			},]
		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);
		$user                               = Auth::user();
        $dataForm['active']                 = (int)isset($dataForm['active']) && $dataForm['active'] ? 1 : 0;
        $dataForm['receive_notification']   = (int)isset($dataForm['receive_notification']) && $dataForm['receive_notification'] ? 1 : 0;
		$dataForm['user_updated_id']        = (int)$user->id;
		$dataForm['user_updated_at']        = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));

		$userUpdate                         = UserSulRadio::getBy_Id($userId);

		$userUpdate->fill($dataForm);
        $userUpdate->save();
        toastr()->success('Usuário Atualizado com sucesso', 'Sucesso');
        if(isset($dataForm['emissora_id'])){
            return redirect(route('emissora.edit', [$dataForm['emissora_id']]));
        }

		return redirect(route('client.user', [$clientId]));
	}
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'plans'             => Plan::getAll(),
			'hasAdd'            => ResourceAdmin::hasResourceByRouteName('client.create'),
			'hasEdit'           => ResourceAdmin::hasResourceByRouteName('client.edit', [1]),
			'functions'         => Funcao::all(),
			'hasStore'          => ResourceAdmin::hasResourceByRouteName('client.store'),
			'hasUpdate'         => ResourceAdmin::hasResourceByRouteName('client.update', [1]),
			
			'hasPayment'        => ResourceAdmin::hasResourceByRouteName('client.payment', [1]),
			
			'hasUser'           => ResourceAdmin::hasResourceByRouteName('client.user', [1]),
			'hasUserAdd'        => ResourceAdmin::hasResourceByRouteName('client.user.create', [1]),
			'hasUserStore'      => ResourceAdmin::hasResourceByRouteName('client.user.store', [1]),
			'hasUserEdit'       => ResourceAdmin::hasResourceByRouteName('client.user.edit', [1, 1]),
			'hasUserUpdate'     => ResourceAdmin::hasResourceByRouteName('client.user.update', [1, 1]),
			'hasUserNotify'     => ResourceAdmin::hasResourceByRouteName('client.user.notify', [1,1]),
			
			'hasBroadcast'      => ResourceAdmin::hasResourceByRouteName('client.broadcast', [1]),
			'hasBroadcastStore' => ResourceAdmin::hasResourceByRouteName('client.broadcast.store', [1]),
		];
		$this->parameters = $parameters;
	}
}