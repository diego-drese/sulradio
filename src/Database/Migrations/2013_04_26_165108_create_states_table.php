<?php

use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio_mongo';
	public function up() {
		$this->createCountries();
		$this->createStates();
	}
	
	
	private function createCountries() {
		Schema::connection($this->connection)->create('countries', function (Blueprint $table) {
			$table->background('id');
			$table->background('title');
		});
	}
	
	private function createStates() {
		Schema::connection($this->connection)->create('states', function (Blueprint $table) {
			$table->background('id');
			$table->background('title');
			$table->background('country_id');
			$table->background('letter');
			$table->background('iso');
			$table->background('slug');
			$table->background('population');
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('states');
	}
	
}
