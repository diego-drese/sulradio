<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemLogTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	protected $connection = 'sulradio';
	public function up() {
		Schema::connection($this->connection)->create('system_log', function (Blueprint $table) {
			$table->increments('id');
			$table->string('subject', 191);
			$table->longText('content');
			$table->smallInteger('zone')
				->index()
				->default(1);
			//1=ticket ,2=sead
			$table->smallInteger('type')
				->index()
				->default(1);
			//1=new ,2=Update, 3=Comment, 4=Transfer Agent, 5=Visualização
			$table->integer('ticket_id')->index()->nullable();
			$table->integer('user_id')->index();
			$table->smallInteger('status')
				->index()
				->default(1);
			//1=new ,2=read
			$table->tinyInteger('only_root')->nullable();
			$table->timestamps();
			$table->index('created_at');
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::connection($this->connection)->dropIfExists('system_log');
	}
	
}
