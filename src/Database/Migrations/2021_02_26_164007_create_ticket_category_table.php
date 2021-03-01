<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketCategoryTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('ticket_category', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 191);
			$table->string('color', 191);
			$table->tinyInteger('is_active', )->default(1)->index(); //1=Active 0=Inactive
			$table->text('description')->nullable();
			$table->timestamps();
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ticket_category');
	}
	
}
