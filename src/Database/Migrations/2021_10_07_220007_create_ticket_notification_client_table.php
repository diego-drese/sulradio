<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketNotificationClientTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket_notification_client', function (Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('type')->index()->default(1); //1=info ,2=question
			$table->integer('ticket_id')->index();
			$table->integer('user_id')->index();
			$table->integer('comment_id')->nullable();
			$table->longText('comment')->nullable();
			$table->string('send_file_1')->nullable();
			$table->string('send_file_2')->nullable();
			$table->string('send_file_3')->nullable();
			$table->string('send_file_4')->nullable();
			$table->string('send_file_5')->nullable();
			$table->string('send_file_6')->nullable();
			$table->string('send_file_7')->nullable();
			$table->string('send_file_8')->nullable();
			$table->string('send_file_9')->nullable();
			$table->string('send_file_10')->nullable();
			$table->timestamp('send_date_at')->nullable();
			$table->integer('total_send')->default(0);
			$table->integer('total_answered')->default(0);
			$table->integer('status')->index()->default(0); //0=waiting_confirm, 1=waiting, 2=processing, 3=send, 3=answered
			$table->timestamps();
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ticket_notification_client');
	}
	
}
