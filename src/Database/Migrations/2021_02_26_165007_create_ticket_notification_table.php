<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketNotificationTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket_notification', function (Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('type')->index()->default(1); //1=new ,2=Update, 3=Comment, 4=Transfer Agent
			$table->integer('ticket_id')->index();
			$table->integer('agent_current_id')->index();
			$table->integer('agent_old_id')->index();
			$table->integer('user_logged')->index();
			$table->integer('owner_id')->index();
			$table->integer('comment_id')->nullable();
			$table->text('users_comments')->nullable();
			$table->integer('status')->index()->default(0); //0=waiting, 1=processing 2=processed
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
		Schema::connection($this->connection)->dropIfExists('ticket_notification');
	}
	
}
