<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AtoNotification extends Model {
	const TABLE = 'ato_notification';
	protected $fillable = [
		'ato_id',
		'user_logged',
		'user_id',
		'total_send',
		'date_sent',
		'error_desc',
		'status',
	];
	const STATUS_UNDEFINED = -1;
	const STATUS_WAITING = 0;
	const STATUS_PROCESSING = 1;
	const STATUS_SEND = 2;
	const STATUS_ERROR = 99;

	const STATUS_TEXT = [
		self::STATUS_UNDEFINED => 'Indefinido',
		self::STATUS_WAITING => 'Aguardando disparo',
		self::STATUS_PROCESSING=> 'Processando',
		self::STATUS_SEND=> 'Enviado',
		self::STATUS_ERROR=> 'ERRO',
		];

	protected $table = 'ato_notification';
	protected $connection = 'sulradio';

	public static function getStatusText($status){
		return self::STATUS_TEXT[$status] ?? self::STATUS_TEXT[self::STATUS_UNDEFINED];
	}

	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}
    public function getDateSentAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}
	public function getCreatedAtAttribute($value) {
		return $value ? (new Carbon($value))->timezone('America/Sao_paulo')->format('d/m/Y H:i') : '';
	}

	public static function getById($id) {
		return self::where('id', $id)->first();
	}

    public static function getUsersByAtoId($id) {
		$usersNotified = self::where('ato_id', $id)->get();

        foreach ($usersNotified as &$notified){
            $userInfo = UserSulRadio::getByIdStatic($notified->user_id);
            $notified->status_send =  self::getStatusText($notified->status);
            $notified->email = $userInfo->email;
            $notified->name = $userInfo->name.' '.$userInfo->lastname;
            $notified->receive_notification = 1;
        }
        return $usersNotified;
	}

	public static function add($emissora, $ato, $userLogged) {
        $usersClients       =  Client::getUsersByEmissora($emissora->emissoraID);
        foreach ($usersClients as $user){
            if($user->active){
                self::create(
                    [
                        'ato_id'=>$ato->atoID,
                        'user_logged'=>$userLogged->id,
                        'user_id'=>$user->id,
                        'total_send'=>0,
                        'status'=>self::STATUS_WAITING,
                    ]
                );
            }
        }
	}

	public static function getToNotify() {
		$notifications = self::where('status', self::STATUS_WAITING)
			->where('updated_at', '<', Carbon::now()->subMinutes(3)->toDateTimeString())
			->limit(50)
			->get();
        if ($notifications){
            self::whereIn('id', $notifications->pluck('id')->toArray())->update(['status'=>self::STATUS_PROCESSING]);
        }

		return $notifications;
	}


}
