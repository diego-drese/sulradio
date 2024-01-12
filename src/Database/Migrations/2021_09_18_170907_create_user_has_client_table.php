<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateUserHasClientTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('user_has_client')) {
            Schema::connection($this->connection)->create('user_has_client', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->index();
                $table->integer('client_id')->index();
                $table->integer('create_user_id')->index();
                $table->integer('update_user_id')->nullable()->index();
                $table->timestamps();
            });
        }
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('user_has_client');
	}
	
}
