<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateTicketCommentTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(Schema::connection($this->connection)->hasTable('ticket_comment')) {
            Schema::connection($this->connection)
                ->table('ticket_comment', function ($table) {
                    $table->tinyInteger('send_client')->nullable()->defaut('0')->index();
                });
        }
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        if(Schema::connection($this->connection)->hasTable('ticket_comment')) {
            Schema::connection($this->connection)->table('ticket_comment', function (Blueprint $table) {
                $table->dropColumn('send_client');
            });
        }
	}
	
}
