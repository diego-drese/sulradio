<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDocumentTable extends Migration {
	
	protected $connection = 'sulradio';
	
	public function up() {
		Schema::connection($this->connection)
			->table('document', function(Blueprint $table) {
				$table->integer('document_folder_id')->nullable()->index();
			});
	}
	
	public function down() {
		Schema::connection($this->connection)->table('document', function (Blueprint $table) {
			$table->dropColumn('document_folder_id');
		});
	}
}
