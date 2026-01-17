<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\DB;
class CreateWhatsappNotificationTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
        if(!Schema::connection($this->connection)->hasTable('whatsapp_notifications')) {
            Schema::connection($this->connection)->create('whatsapp_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->index();
                $table->foreignId('ticket_id')->index();
                $table->foreignId('ticket_comment_id')->nullable()->index();
                $table->string('type')->nullable()->index();
                $table->string('destination')->index();
                $table->string('transaction_id')->index();
                $table->string('code')->nullable()->index();
                $table->string('status')->default('enviado')->index();
                $table->string('description')->nullable();
                $table->text('body')->nullable();
                $table->timestamp('sent_at')->nullable()->index();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->index();
            });
        }
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('whatsapp_notifications');
	}
	
}
