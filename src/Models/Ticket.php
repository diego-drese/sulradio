<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\Admin\Models\Resource;

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
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getByIdOwner($id, $owner) {
		$hasAdmin = ResourceAdmin::hasResourceByRouteName('ticket.admin');
		$query = self::where('id', $id);
		if(!$hasAdmin){
			$query->where('agent_id', $owner->id);
		}
		return $query->first();
	}
}
