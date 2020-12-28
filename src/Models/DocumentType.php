<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class DocumentType extends Model {
	const TABLE = 'document_type';
	protected $fillable = [
		'name',
		'description',
		'is_active'
	];
	protected $table = 'document_type';
	protected $connection = 'sulradio_mongo';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	public static function getById($id) {
		return self::where('_id', new \MongoDB\BSON\ObjectId($id))->first();
	}
}
