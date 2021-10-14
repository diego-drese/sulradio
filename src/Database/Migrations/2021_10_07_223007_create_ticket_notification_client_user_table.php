<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketNotificationClientUserTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket_notification_client_user', function (Blueprint $table) {
			$table->increments('id');
			$table->string('identify')->index();
			$table->integer('ticket_notification_client_id')->index('ticket_notification_client_id');
			$table->integer('user_id')->index();
			$table->string('user_name')->nullable();
			$table->string('user_email')->nullable();
			$table->timestamp('send_date_at')->nullable();
			$table->text('answer')->nullable();
			$table->string('answer_file_1')->nullable();
			$table->string('answer_file_2')->nullable();
			$table->string('answer_file_3')->nullable();
			$table->string('answer_file_4')->nullable();
			$table->string('answer_file_5')->nullable();
			$table->string('answer_file_6')->nullable();
			$table->string('answer_file_7')->nullable();
			$table->string('answer_file_8')->nullable();
			$table->string('answer_file_9')->nullable();
			$table->string('answer_file_10')->nullable();
			$table->timestamp('answer_date_at')->nullable();
			$table->integer('status')->index()->default(1); //1=waiting,2=sent, 3=answered
			$table->timestamps();
			$table->index('updated_at');
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ticket_notification_client_user');
	}
	
}
