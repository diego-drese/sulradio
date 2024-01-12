<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketCategoryProfileTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('ticket_category_profile')) {
            Schema::connection($this->connection)->create('ticket_category_profile', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('ticket_category_id')->index();
                $table->integer('profile_id')->index();
                $table->integer('user_id')->index();
                $table->integer('user_id_removed')->index()->default(0);
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
		Schema::connection($this->connection)->dropIfExists('ticket_category_profile');
	}
	
}
