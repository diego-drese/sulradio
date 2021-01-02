<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Oka6\SulRadio\Helpers\Helper;
use function GuzzleHttp\json_decode;

class Document extends Model {
	const TABLE = 'document';
	protected $fillable = [
		'name',
		'description',
		'document_type_id',
		'version',
		'document_id',
		'emissora_id',
		'file_name',
		'file_type',
		'file_preview',
		'file_size',
		'historic',
		'status',
		'validated',
	];
	const STATUS_CURRENT_VERSION = 1;
	const STATUS_OLD_VERSION = 0;
	
	protected $table = 'document';
	protected $connection = 'sulradio';
	public static function getPathUpload($fileName=""){
		return storage_path('uploads_sulradio').'/'.$fileName;
	}
	public function scopeWithDocumentType($query) {
		$query->join('document_type', 'document_type.id', 'document.document_type_id');
		return $query;
	}
	public function scopeCurrentVersion($query) {
		$query->where('document.status', self::STATUS_CURRENT_VERSION);
		return $query;
	}
	
	public function scopeFilterClient($query, $clientId) {
		if($clientId){
			$client = Client::getById($clientId);
			$query->whereIn('emissora_id', json_decode($client->broadcast));
		}
		return $query;
	}
	
	public function scopeWithEmissora($query) {
		$query->join('emissora', 'emissora.emissoraID', 'document.emissora_id');
		return $query;
	}
	
	public function getValidatedAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y') : '';
	}
	
	public function setValidatedAttribute($value) {
		$this->attributes['validated'] = Helper::convertDateBrToMysql($value);
	}
	
	public static function getById($id, $user) {
		return self::where('id', $id)
			->filterClient($user->client_id)
			->first();
	}
	
	public function getFileSizeAttribute($value) {
		if( $value >= 1<<30)
			return number_format($value/(1<<30),2)."GB";
		else if( $value >= 1<<20 )
			return number_format($value/(1<<20),2)."MB";
		else if( $value >= 1<<10)
			return number_format($value/(1<<10),2)."KB";
		return number_format($value)." bytes";
	}
	
	public function getFileTypeAttribute($value) {
		if( $value == 'application/pdf')
			return '<span class="fas fa-file-pdf font-24" title="PDF"></span>';
		else if( $value == 'application/vnd.ms-excel' || $value=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
			return '<span class="fas fa-file-excel font-24" title="Excel"></span>';
		else if( $value=="text/csv" || $value=="text/plain")
			return '<span class="fas fa-file font-24" title="CSV"></span>';
		else if( $value=="image/jpeg")
			return '<span class="fas fa-file-image font-24" title="JPEG"></span>';
		else if( $value=="image/png")
			return '<span class="fas fa-file-image font-24" title="PNG"></span>';
		else if( $value=="text/html")
			return '<span class="mdi mdi-language-html5 font-24" title="HTML"></span>';
		else if( $value=="application/msword")
			return '<span class="fas fa-file-word font-24" title="Word"></span>';
		
		return '<span class="fas fa-file font-24 text-danger" title="Naão definido"></span>';
	}
	public static function createOrUpdate($data, $user, $id=null){
		$action             = DocumentHistoric::ACTION_CREATED;
		$data['version']    = 0;
		$data['document_id']= 0;
		$data['status']= Document::STATUS_CURRENT_VERSION;
		if($id){
			$oldDocument            = Document::getById($id, $user);
			if($oldDocument->status!=Document::STATUS_CURRENT_VERSION){
				Log::alert('Document createOrUpdate, documento['.$id.'] já está versionado');
				return false;
			}
			$oldDocument->status    = Document::STATUS_OLD_VERSION;
			$oldDocument->save();
			$action                 = DocumentHistoric::ACTION_UPDATED;
			$data['version']        = $oldDocument->version+1;
			$data['document_id']    = $oldDocument->id;
		}
		$document = Document::create($data);
		DocumentHistoric::create([
			'user_id'=> $user->id,
			'document_id'=> $document->id,
			'action'=> $action,
		]);
		
	}
	
}
