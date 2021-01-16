<?php

namespace Oka6\SulRadio\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\Admin\Models\Sequence;
use Oka6\SulRadio\Helpers\Helper;

class DouType extends Model {
	const TABLE = 'dou_type';
	protected $fillable = [
		'id',
		'name',
		'slug'
	];
	protected $table = 'dou_type';
	protected $connection = 'sulradio_mongo';
	
	public static function createOrUpdate($name) {
		$slug = Helper::slugify($name);
		$douType = self::getBySlugWithCache($slug);
		if (!$douType){
			$douType = self::create([
				'id'=> Sequence::getSequence(self::TABLE),
				'name'=> $name,
				'slug'=> $slug,
			]);
		}
		return $douType;
	}
	
	public static function getBySlugWithCache($slug) {
		return Cache::tags(['sulradio'])->remember('dou-type-'.$slug, 120, function () use($slug){
			return self::where('slug', $slug)->first();
		});
	}
	
	public static function searchCategory($text) {
		$slug = Helper::slugify($text);
		return self::where('slug','like', '%'.$slug.'%')->limit(10)->get();
	}
	
}
