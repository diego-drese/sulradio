<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Library\MongoUtils;
use Oka6\Admin\Models\PasswordReset;
use Oka6\Admin\Models\Profile;
use Oka6\Admin\Models\Sequence;
use Oka6\Admin\Models\User;
use Oka6\Admin\Notifications\ResetPasswordNotification;
use Oka6\SulRadio\Models\UserHasClient;
use Oka6\SulRadio\Models\UserSulRadio;
use Yajra\DataTables\DataTables;

class UsersController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$model = UserSulRadio::where('id','!=', -1)->whereNull('client_id');
			return DataTables::of($model)
				->filter(function ($query) use($request) {
					$active = $request->get('active');
					if($active || $active=='0'){
						$query->where('active', (int)$request->get('active'));
					}
				})
				->addColumn('edit_url', function ($row) {
					return route('sulradio.user.edit', [$row->id]);
				})->addColumn('last_login_at', function ($row) {
					return $row->last_login_at??'---';
				})->addColumn('profile_name', function ($row) {
					return $row->profile_name??'---';
				})->addColumn('created', function ($row) {
					return $row->user_created_id ? UserSulRadio::getByIdStatic($row->user_created_id)->name : '---';
				})->addColumn('clients', function ($row) {
					return UserHasClient::getAllClients($row->id)->pluck('company_name')->toArray();
				})->toJson();
		}
		return $this->renderView('SulRadio::backend.user.index', []);
	}
	
	public function create(UserSulRadio $data) {
		return $this->renderView('SulRadio::backend.user.create', ['data' => $data, 'clientsSelected'=> [], 'userTicket'=>[]]);
	}

	public function notifyUser($idUser){
		$user = UserSulRadio::where('id', (int)$idUser)->first();
		try {
			$passwordReset = PasswordReset::create(['email' => $user->email, 'token' => $user->remember_token, 'new_account' => true]);
			$user->notify(new ResetPasswordNotification($passwordReset));
			$user->last_notification_at = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
			$user->save();
			toastr()->success('Email enviado com sucesso', 'Sucesso');
		}catch (\Exception $e){
			toastr()->error('Não foi possivel enviar o email '.$e->getMessage().'', 'Atenção');
		}
	}

	public function store(Request $request) {
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'profile_id' => 'required|integer',
			'email' => ['required', function ($attribute, $value, $fail) {
				$result = User::where('email', $value)->first();
				if ($result) {
					$fail($attribute . ' já existe cadastrado.');
				}
			},]

		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);
		$user = Auth::user();
		$dataForm['id']                     = Sequence::getSequence('users');
		$dataForm['active']                 = (int)$request->get('active');
		$dataForm['client_id']              = null;
		$dataForm['receive_notification']   = null;
		$dataForm['users_ticket']           = array_map( 'intval', $request->get('users_ticket', []));
		$dataForm['password']               = bcrypt(Str::random(10));
		$dataForm['remember_token']         = Str::random(60);
		$dataForm['user_created_id']        = (int)$user->id;
		$dataForm['user_updated_id']        = (int)$user->id;
		$dataForm['user_updated_at']        = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));

		UserSulRadio::create($dataForm);
		UserHasClient::createOrUpdate($request->get('clients'), $dataForm['id'], $user);
		$this->notifyUser($dataForm['id']);
		toastr()->success('Usuário criado com sucesso', 'Sucesso');
		return redirect(route('sulradio.user.index'));

	}
	
	public function edit($id) {
		$data = UserSulRadio::where('id', (int)$id)->where('id','>',  0)->first();
		if(!$data){
			$userLogged = Auth::user();
			Log::error('UsersController edit, error load user', ['user'=>$userLogged, 'id'=>$id]);
			return redirect(route('admin.page403get'));
		}
		if($data->client_id){
			$userLogged = Auth::user();
			Log::error('UsersController edit, user has client', ['user'=>$userLogged, 'id'=>$id]);
			return redirect(route('admin.page403get'));
		}
		$userTicket = [];
		if(isset($data->users_ticket) && is_array($data->users_ticket)){
			$userTicket=User::whereIn('id', $data->users_ticket)->get();
		}
		return $this->renderView('SulRadio::backend.user.edit', ['data' => $data, 'clientsSelected'=> UserHasClient::getAllClients($data->id), 'userTicket'=>$userTicket]);
	}
	
	public function update(Request $request, $id) {
		$data = UserSulRadio::getByIdStatic($id);
		$dataForm = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'profile_id' => 'required|integer',
			'email' => ['required', function ($attribute, $value, $fail) use($id) {
				$result = User::where('email', $value)->where('id', '!=', (int)$id)->first();
				if ($result) {
					$fail($attribute . ' já existe cadastrado.');
				}
			},]

		], ['required' => 'Campo obrigatório', 'unique' => 'Email já cadastrado']);
		$user = Auth::user();
		$dataForm['active']             = (int)$request->get('active');
		$dataForm['users_ticket']       = array_map( 'intval', $request->get('users_ticket', []));
		$dataForm['user_updated_id']    = (int)$user->id;
		$dataForm['user_updated_at']    = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
		$data->fill($dataForm);
		$data->save();
		UserHasClient::createOrUpdate($request->get('clients'), $id, $data);
		Cache::tags('sulradio')->flush();
		toastr()->success("{$data->name} Atualizado com sucesso", 'Sucesso');
		return redirect(route('sulradio.user.index'));
	}

	protected function makeParameters($extraParameter = null) {
		$profiles =  Profile::where('id', '!=', User::PROFILE_ID_ROOT)->where('type', 'Admin')->get();
		$parameters = [
			'profiles'          => $profiles,
			'hasAdd'            => ResourceAdmin::hasResourceByRouteName('sulradio.user.create'),
			'hasEdit'           => ResourceAdmin::hasResourceByRouteName('sulradio.user.edit', [1]),
			'hasStore'          => ResourceAdmin::hasResourceByRouteName('sulradio.user.store'),
			'hasUpdate'         => ResourceAdmin::hasResourceByRouteName('sulradio.user.update', [1]),
		];
		$this->parameters = $parameters;
	}
}