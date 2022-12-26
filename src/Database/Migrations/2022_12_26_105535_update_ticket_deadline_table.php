<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateTicketDeadlineTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)
			->table('ticket', function( $table) {
				$table->tinyInteger('send_deadline')->nullable()->defaut(1)->index();
			});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->table('ticket', function (Blueprint $table) {
			$table->dropColumn('send_deadline');
		});
	}
	
}
