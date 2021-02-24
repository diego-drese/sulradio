<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDocumentGoalTable extends Migration {
	
	protected $connection = 'sulradio';
	
	public function up() {
		Schema::connection($this->connection)
			->table('document', function(Blueprint $table) {
				$table->enum('goal', ['Cliente', 'Engenharia', 'Jurídico'])->default('Cliente')->index();
			});
	}
	
	public function down() {
		Schema::connection($this->connection)->table('document', function (Blueprint $table) {
			$table->dropColumn('goal');
		});
	}
}
