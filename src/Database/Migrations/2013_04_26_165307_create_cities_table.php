<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

class CreateCitiesTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio_mongo';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('cities')) {
            Schema::connection($this->connection)->create('cities', function (Blueprint $table) {
                $table->background('id');
                $table->background('title');
                $table->background('iso');
                $table->background('iso_ddd');
                $table->background('status');
                $table->background('slug');
                $table->background('population');
                $table->background('lat');
                $table->background('long');
                $table->background('income_per_capita');
            });
        }
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('cities');
	}
	
}
