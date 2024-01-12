<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateAtoNotificationTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('ato_notification')) {
            Schema::connection($this->connection)->create('ato_notification', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('ato_id')->index();
                $table->integer('user_logged')->index();
                $table->integer('user_id')->index();
                $table->integer('total_send')->default(0);
                $table->timestamp('date_sent')->nullable();
                $table->text('error_desc')->nullable();
                $table->integer('status')->index()->default(0); //0=waiting, 1=processing 2=processed 99=error
                $table->timestamps();
                $table->index('updated_at');
            });
        }
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('ato_notification');
	}
	
}
