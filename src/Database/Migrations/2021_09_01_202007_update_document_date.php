<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDocumentDate extends Migration {
	
	protected $connection = 'sulradio';
	
	public function up() {
		Schema::connection($this->connection)
			->table('document', function(Blueprint $table) {
				$table->timestamp('date_document')->nullable()->index();
			});
	}
	
	public function down() {
		Schema::connection($this->connection)->table('document', function (Blueprint $table) {
			$table->dropColumn('date_document');
		});
	}
}
