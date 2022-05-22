<?php

namespace Oka6\SulRadio\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Models\User;

class TicketDocument extends Model {
	const TABLE = 'ticket_document';
	protected $appends=['file_size_format'];
	protected $fillable = [
		'ticket_id',
		'user_id',
		'file_name',
		'file_name_original',
		'file_type',
		'file_type',
		'file_preview',
		'file_size',
		'removed',
	];

	protected $table = 'ticket_document';
	protected $connection = 'sulradio';
	
	public function getUpdatedAtAttribute($value) {
		return $value ? (new Carbon($value))->format('d/m/Y H:i') : '';
	}

	public function getFileSizeFormatAttribute() {
		if( $this->file_size >= 1<<30)
			return number_format($this->file_size/(1<<30),2)."GB";
		else if( $this->file_size >= 1<<20 )
			return number_format($this->file_size/(1<<20),2)."MB";
		else if( $this->file_size >= 1<<10)
			return number_format($this->file_size/(1<<10),2)."KB";
		return number_format($this->file_size)." bytes";
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
		
		return '<span class="fas fa-file font-24 text-danger" title="Não definido"></span>';
	}

	public static function getById($id) {
		return self::where('id', $id)->first();
	}
	public static function getAllByTicketId($id, $showRemoved=0) {
		$query = self::where('ticket_document.ticket_id', $id)
				->orderBy('ticket_document.file_name_original');
		if(!$showRemoved){
			$query->where('removed', 0);
		}
		return	$query->get();
	}
	
	public static function removeById($id, $hasAdmin) {
        if($hasAdmin){
            $remove = self::where('id', $id)->first();
            $remove->delete();
            Storage::disk('spaces')->delete('tickets/'.$remove->file_name);
            return $remove;
        }
		return null;
	}

    public static function archivedById($id) {
		$query = self::where('id', $id);
		$document = $query->first();
		$document->removed=1;
		$document->save();
		return $document;
	}
	
	public static function getWithCache() {
		return Cache::tags(['sulradio'])->remember('ticket_category-', 120, function ()  {
			return self::where('is_active', 1)
				->orderBy('name', 'asc')
				->get();
		});
	}
}
