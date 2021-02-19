<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;

class DocumentHistoric extends Model {
	const TABLE = 'document_historic';
	protected $fillable = [
		'user_id',
		'document_id',
		'action'
	];
	protected $table = 'document_historic';
	protected $connection = 'sulradio';
	
	const ACTION_CREATED = 'created';
	const ACTION_UPDATED = 'updated';
	const ACTION_DOWNLOADED = 'downloaded';
	const ACTION_UNDEFINED = 'undefined';
	
	const ACTION_TRANSLATE = [
		self::ACTION_CREATED        => 'Criação',
		self::ACTION_UPDATED        => 'Atualizado',
		self::ACTION_DOWNLOADED     => 'Baixado',
		self::ACTION_UNDEFINED      => 'Indefinido',
	];
	
	public function getActiontAttribute($value) {
		return $value  && isset(DocumentHistoric::ACTION_TRANSLATE[$value])? DocumentHistoric::ACTION_TRANSLATE[$value] : DocumentHistoric::ACTION_TRANSLATE['undefined'];
	}
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getTimeLineById($id, $user, $adjustUserName = true) {
		$document = new Document();
		$query = self::where('document.id', $id)
			->select(
				'document_historic.*',
				'document.document_id as document_id_version',
				'document.name as document_name',
				'document.file_name as file_name',
				'document.file_type as file_type',
				'document.file_type as file_type',
				'document.file_size as file_size',
				'document_type.name as document_type_name',
				'document_folder.name as document_folder_name',
			)
			->join('document', 'document.id', 'document_historic.document_id')
			->join('document_type', 'document_type.id', 'document.document_type_id')
			->leftJoin('document_folder', 'document_folder.id', 'document.document_folder_id')
			->orderBy('document_historic.id', 'DESC');
		
		if($user->client_id){
			$client = Client::getById($user->client_id);
			$query->whereIn('document.emissora_id', json_decode($client->broadcast));
		}
		$timeline           = $query->get();
		$totalTimeLine      = count($timeline);
		$documentHasInclude = [];
		for ($i=0; $i<$totalTimeLine; $i++){
			if($timeline[$i]->document_id_version>0 && !in_array($timeline[$i]->document_id_version, $documentHasInclude)){
				$documentHasInclude[] = $timeline[$i]->document_id_version;
				$mergeTimeLine  = self::getTimeLineById($timeline[$i]->document_id_version, $user, false);
				foreach ($mergeTimeLine as $merge){
					$timeline[] = $merge;
				}
			}
		}
		
		if($adjustUserName){
			foreach ($timeline as &$item){
				$user               = User::where('id', (int)$item->user_id)->first();
				$item->user_picture = $user->picture;
				$item->user_name    = $user->name;
				$item->action       = DocumentHistoric::ACTION_TRANSLATE[$item->action];
				$item->date         = $item->created_at->format('d/m/Y H:i');
				$item->file_size    = $document->getFileSizeAttribute($item->file_size);
				$item->download     = route('document.download', [$item->document_id]);
			}
		}
		return $timeline;
	}
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('document_type', 120, function () {
			return self::get();
		});
	}
}
