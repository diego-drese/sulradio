<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateDouTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio_mongo';
	public function up() {
		Schema::connection($this->connection)->create('dou', function (Blueprint $table) {
			$table->background('id');
			$table->background('name');
			$table->background('id_oficio');
			$table->background('pub_name');
			$table->background('type_id');
			$table->background('type_name');
			$table->background('date');
			$table->background('categories.id');
			$table->background('categories.name');
			$table->background('page_number');
			$table->background('edition_number');
			$table->background('id_materia');
			$table->background('identifica');
			$table->background('data');
			$table->background('ementa');
			$table->background('titulo');
			$table->background('sub_titulo');
			$table->background('created_at');
			$table->background('updated_at');
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('dou');
	}
	
}
