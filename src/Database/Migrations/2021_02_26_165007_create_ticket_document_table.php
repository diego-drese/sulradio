<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketDocumentTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket_document', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('document_id')->index();
			$table->integer('ticket_id')->index();
			$table->integer('user_id')->index();
			$table->tinyInteger('removed')->index()->default(0);
			$table->string('file_name', 191)->nullable();
			$table->string('file_type', 191)->nullable();;
			$table->string('file_preview', 191)->nullable();;
			$table->string('file_size', 191)->nullable();;
			$table->timestamps();
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ticket_document');
	}
	
}
