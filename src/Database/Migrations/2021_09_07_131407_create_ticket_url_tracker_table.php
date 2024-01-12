<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketUrlTrackerTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('ticket_url_tracker')) {
            Schema::connection($this->connection)->create('ticket_url_tracker', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('ticket_id')->index();
                $table->string('name', 191);
                $table->string('description', 191)->nullable();
                $table->text('url');
                $table->timestamp('last_modify')->index()->nullable();
                $table->timestamp('last_tracker')->index()->nullable();
                $table->text('hash')->nullable();
                $table->integer('status')->index()->default(1); //1=Active 0=Inactive
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
		Schema::connection($this->connection)->dropIfExists('ticket_url_tracker');
	}
	
}
