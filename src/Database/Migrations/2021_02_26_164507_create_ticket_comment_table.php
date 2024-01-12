<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketCommentTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('ticket_comment')) {
            Schema::connection($this->connection)->create('ticket_comment', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('html');
                $table->integer('user_id')->index();
                $table->integer('ticket_id')->index();
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
		Schema::connection($this->connection)->dropIfExists('ticket_comment');
	}
	
}
