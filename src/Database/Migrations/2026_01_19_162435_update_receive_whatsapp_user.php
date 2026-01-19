<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UpdateReceiveWhatsappUser extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'oka6_admin';
	public function up() {
        if(Schema::connection($this->connection)->hasTable('users')) {
            Schema::connection($this->connection)
                ->table('users', function ($table) {
                    $table->tinyInteger('receive_whatsapp')->nullable()->defaut(1)->index();
                });
        }
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
        if(Schema::connection($this->connection)->hasTable('users')) {
            Schema::connection($this->connection)->table('ticket', function (Blueprint $table) {
                $table->dropColumn('receive_whatsapp');
            });
        }
	}
	
}
