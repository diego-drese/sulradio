<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateTicketStatusDeadlineTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(Schema::connection($this->connection)->hasTable('ticket_status')) {
            Schema::connection($this->connection)
                ->table('ticket_status', function ($table) {
                    $table->enum('send_deadline', ['deadline', 'protocol_deadline'])->nullable()->index();
                });
        }
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        if(Schema::connection($this->connection)->hasTable('ticket_status')) {
            Schema::connection($this->connection)->table('ticket_status', function (Blueprint $table) {
                $table->dropColumn('send_deadline');
            });
        }
	}
	
}
