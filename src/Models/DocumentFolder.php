<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Oka6\SulRadio\Helpers\Helper;

class DocumentFolder extends Model {
	const TABLE = 'document_folder';
	protected $fillable = [
		'name',
		'description',
		'is_active',
		'goal',
	];
	protected $table = 'document_folder';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}
	
	
	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getWithCache($goal) {
		return Cache::tags(['sulradio'])->remember('document_folder-'.$goal, 120, function () use($goal){
			return self::where('is_active', 1)
				->where('goal', $goal)
				->orderBy('name', 'asc')
				->get();
		});
	}
}
