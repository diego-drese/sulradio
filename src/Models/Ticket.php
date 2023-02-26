<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\Resource;
use function GuzzleHttp\json_decode;
use function Sodium\add;

class Ticket extends Model {
	const TABLE = 'ticket';
	protected $fillable = [
		'subject',
		'content',
		'html',
		'status_id',
		'priority_id',
		'owner_id',
		'agent_id',
		'category_id',
		'emissora_id',
		'completed_at',
		'start_forecast',
		'end_forecast',
		'show_client',
		'send_deadline',
        'renewal_alert'
	];

	protected $table = 'ticket';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : '';
	}
	public function scopeWithSelectDataTable($query) {
		return $query->select('ticket.*',
			'ticket_status.name as status_name',
			'ticket_status.color as status_color',
			'ticket_priority.name as priority_name',
			'ticket_priority.color as priority_color',
			'ticket_category.name as category_name',
			'ticket_category.color as category_color',
			'servico.desc_servico as desc_servico',
			'municipio.desc_municipio as desc_municipio',
			'uf.desc_uf as desc_uf',
			'emissora.razao_social as emissora');
	}
	public function scopeWithStatus($query) {
		return $query->join('ticket_status', 'ticket_status.id', 'ticket.status_id');
	}
	public function scopeWithPriority($query) {
		return $query->join('ticket_priority', 'ticket_priority.id', 'ticket.priority_id');
	}
	public function scopeWithCategory($query) {
		return $query->join('ticket_category', 'ticket_category.id', 'ticket.category_id');
	}
	public function scopeWithParticipants($query, $user, $hasAdmin) {
		$query->join('ticket_participant', 'ticket_participant.ticket_id', 'ticket.id');
		if(!$hasAdmin){
			$query->where(function ($orWhere) use($user){
				$orWhere->where('ticket_participant.user_id', $user->id)
					->orWhere('ticket.owner_id', $user->id);
			});
		}
		return $query;
	}

	public function scopeFilterClient($query, $user) {
		if($user->client_id){
			$client = Client::getById($user->client_id);
			$query->whereIn('emissora_id', json_decode($client->broadcast));
		}
		return $query;
	}

	public function scopeWithEmissora($query) {
		return $query->leftJoin('emissora', 'emissora.emissoraID', 'ticket.emissora_id');
	}
	public function getStartForecastAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	public function scopeWithLocalidade($query) {
		$query->leftJoin('municipio', 'municipio.municipioID', 'emissora.municipioID');
		return $query;
	}
	public function scopeWithServico($query) {
		$query->leftJoin('servico', 'servico.servicoID', 'emissora.servicoID');
		return $query;
	}
	public function scopeWithUf($query) {
		$query->leftJoin('uf', 'uf.ufID', 'municipio.ufID');
		return $query;
	}
	public function getEndForecastAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
    public function getRenewalAlertAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	public static function getById($id) {
		return self::where('id', $id)->first();
	}

	public static function getAllByOwnerId($id, $completed=null) {
		$query = self::selectRaw('COUNT(1) as total, ticket_status.name as status_name, ticket_status.color as status_color')
			->join('ticket_status', 'ticket_status.id', 'ticket.status_id')
			->where('owner_id', $id)
			->groupBy('status_name')
			->groupBy('status_color');
		if($completed){
			$query->whereNotNull('completed_at');
		}else{
			$query->whereNull('completed_at');
		}
		return $query->get();
		
	}
	public static function getAllByAgentId($id, $completed=null, $reach=null) {
		$query = self::selectRaw('COUNT(1) as total, ticket_status.name as status_name, ticket_status.color as status_color')
			->join('ticket_status', 'ticket_status.id', 'ticket.status_id')
			->join('ticket_participant', 'ticket_participant.ticket_id', 'ticket.id')
			->where('ticket_participant.user_id', $id)
			->groupBy('status_name')
			->groupBy('status_color');
		if($completed){
			$query->whereNotNull('completed_at');
		}else{
			$query->whereNull('completed_at');
			if($reach){
				$query->where('end_forecast', '<=', Carbon::now()->addDays(7));
			}
		}
		return $query->get();
	}

    public static function getDeadLines($day, $statusIdDeadLine) {
        $now = Carbon::now()->addDays($day);
		return self::where('start_forecast', '>=', $now->format('Y-m-d 00:00:00'))
            ->where('start_forecast', '<=', $now->format('Y-m-d 23:59:59'))
            ->where('status_id', $statusIdDeadLine)
            ->get();

	}

    public static function getProtocolDeadLines($day, $statusIdDeadLine) {
    $now = Carbon::now()->addDays($day);
    return self::where('end_forecast', '>=', $now->format('Y-m-d 00:00:00'))
        ->where('end_forecast', '<=', $now->format('Y-m-d 23:59:59'))
        ->where('status_id', $statusIdDeadLine)
        ->get();
	}
    public static function getRenewalAlert($day) {
    $now = Carbon::now()->addDays($day);
    return self::where('renewal_alert', '>=', $now->format('Y-m-d 00:00:00'))
        ->where('renewal_alert', '<=', $now->format('Y-m-d 23:59:59'))
        ->get();
	}
}
