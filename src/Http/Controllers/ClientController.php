<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\Profile;
use Oka6\Admin\Models\Sequence;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Client;
use Oka6\SulRadio\Models\Emissora;
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
			'company_name' => 'required',
			'email' => 'required',
			'document_type' => 'required',
			'plan_id' => 'required',
			'document' => 'required',
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
			'company_name' => 'required',
			'email' => 'required',
			'document_type' => 'required',
			'document' => 'required',
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
					return route('client.user.edit', [$clientId, $row->id]);
				})->addColumn('profile_name', function ($row) {
					$profile = Profile::getById($row->profile_id);
					return $profile->name;
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
			'password' => 'required|min:6|confirmed',
			'password_confirmation' => 'required|min:6|',
			'email' => ['required', function ($attribute, $value, $fail) {
				$result = User::where('email', $value)->first();
				if ($result) {
					$fail($attribute . ' já cadastrado.');
				}
			},]
		
		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);
		$dataForm['id']         = Sequence::getSequence('users');
		$dataForm['client_id']  = $clientId;
		$dataForm['password']   = bcrypt($request->get('password_confirmation'));
		UserSulRadio::create($dataForm);
		toastr()->success('Usuário criado com sucesso', 'Sucesso');
		return redirect(route('client.user', [$clientId]));
	}
	public function userEdit($clientId, $userId) {
		$data = UserSulRadio::getBy_Id($userId);
		return $this->renderView('SulRadio::backend.client.user.edit', ['data' => $data, 'client'=>Client::getById($clientId), 'profiles' => Profile::getProfilesByTypes(Config::get('sulradio.profile_type'))]);
	}
	public function userUpdate(Request $request, $clientId, $userId) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'profile_id' => 'required|integer',
			'password' => 'nullable|min:6|confirmed',
			'password_confirmation' => 'nullable|min:6',
			'email' => ['required', function ($attribute, $value, $fail) use($userId) {
				$result = User::where('email', $value)->where('_id', '!=', new \MongoDB\BSON\ObjectId($userId))->first();
				if ($result) {
					$fail($attribute . ' já cadastrado.');
				}
			},]
		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);
		$user                   = UserSulRadio::getBy_Id($userId);
		if ($request->get('password_confirmation')) {
			$dataForm['password'] = bcrypt($request->get('password_confirmation'));
		}
		$user->update($dataForm);
		toastr()->success('Usuário Atualizado com sucesso', 'Sucesso');
		return redirect(route('client.user', [$clientId]));
	}
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'plans'             => Plan::getAll(),
			'hasAdd'            => ResourceAdmin::hasResourceByRouteName('client.create'),
			'hasEdit'           => ResourceAdmin::hasResourceByRouteName('client.edit', [1]),
			'hasStore'          => ResourceAdmin::hasResourceByRouteName('client.store'),
			'hasUpdate'         => ResourceAdmin::hasResourceByRouteName('client.update', [1]),
			
			'hasPayment'        => ResourceAdmin::hasResourceByRouteName('client.payment', [1]),
			
			'hasUser'           => ResourceAdmin::hasResourceByRouteName('client.user', [1]),
			'hasUserAdd'        => ResourceAdmin::hasResourceByRouteName('client.user.create', [1]),
			'hasUserStore'      => ResourceAdmin::hasResourceByRouteName('client.user.store', [1]),
			'hasUserEdit'       => ResourceAdmin::hasResourceByRouteName('client.user.edit', [1, 1]),
			'hasUserUpdate'     => ResourceAdmin::hasResourceByRouteName('client.user.update', [1, 1]),
			
			'hasBroadcast'      => ResourceAdmin::hasResourceByRouteName('client.broadcast', [1]),
			'hasBroadcastStore' => ResourceAdmin::hasResourceByRouteName('client.broadcast.store', [1]),
		];
		$this->parameters = $parameters;
	}
}