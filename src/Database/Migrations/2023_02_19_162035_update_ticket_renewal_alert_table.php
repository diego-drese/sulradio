<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateTicketRenewalAlertTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(Schema::connection($this->connection)->hasTable('ticket')) {
            Schema::connection($this->connection)
                ->table('ticket', function ($table) {
                    $table->timestamp('renewal_alert')->nullable()->index();
                });
        }
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        if(Schema::connection($this->connection)->hasTable('ticket')) {
            Schema::connection($this->connection)->table('ticket', function (Blueprint $table) {
                $table->dropColumn('renewal_alert');
            });
        }
	}
	
}
