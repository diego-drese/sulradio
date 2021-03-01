<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket', function (Blueprint $table) {
			$table->increments('id');
			$table->string('subject', 191);
			$table->longText('content');
			$table->longText('html');
			$table->integer('status_id')->index();
			$table->integer('priority_id')->index();
			$table->integer('owner_id')->index(); //Quem criou
			$table->integer('agent_id')->index();//Responsavel
			$table->integer('category_id')->index();
			$table->integer('emissora_id')->index()->nullable();
			$table->timestamp('completed_at')->index()->nullable();
			$table->timestamps();
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ticket');
	}
	
}
