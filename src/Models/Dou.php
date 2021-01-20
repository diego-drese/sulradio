<?php

namespace Oka6\SulRadio\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Helpers\Helper;

class Dou extends Model {
	const TABLE = 'dou';
	protected $fillable = [
		'id',
		'name',
		'id_oficio',
		'pub_name',
		'type_id',
		'type_name',
		'date',
		'categories',
		'page_number',
		'edition_number',
		'id_materia',
		'identifica',
		'data',
		'ementa',
		'titulo',
		'sub_titulo',
		'text',
	];
	
	protected $table = 'dou';
	protected $connection = 'sulradio_mongo';
	protected $dates  = ['created_at', 'updated_at', 'date'];
	
	public static function getById($id){
		return self::where('id', $id)->first();
	}
	
	
	public static function scopeWithCategories($query, $categories){
		if(!is_array($categories)){
			return $query;
		}
		return $query->whereIn('categories.id', array_map('intval', $categories));
	}
	
	public static function scopeWithTypes($query, $types){
		if(!is_array($types)){
			return $query;
		}
		return $query->whereIn('type_id', array_map('intval', $types));
	}
	public static function scopeWithSubject($query, $subject){
		if(empty($subject)){
			return $query;
		}
		return $query->where(function($q) use($subject){
			$q->where('identifica', 'like' , '%'.$subject.'%')
				->orWhere('name', 'like', '%'.$subject.'%');
		});
	}public static function scopeWithPub($query, $pub){
		if(!is_array($pub)){
			return $query;
		}
		return $query->whereIn('pub_name', array_map('strval', $pub));
	}
	
	public static function scopeWithPeriod($query, $period){
		$split = explode(' - ',$period);
		if(count($split)!=2){
			return $query;
		}
		$dateStart  = new \DateTime(Helper::convertDateBrToMysql($split[0].' 00:00:00'));
		$dateEnd    = new \DateTime(Helper::convertDateBrToMysql($split[1].' 23:59:59'));
		
		return $query->where('date', '>=', MongoUtils::convertDatePhpToMongo($dateStart))
			->where('date', '<=', MongoUtils::convertDatePhpToMongo($dateEnd));
	}
	
}
