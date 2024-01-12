<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	
	class CreatePlanTable extends Migration {
		
		protected $connection = 'sulradio';
		
		public function up() {
            if(!Schema::connection($this->connection)->hasTable('plan')) {
                Schema::connection($this->connection)
                    ->create('plan', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name', 191);
                        $table->decimal('value', 10, 2);
                        $table->smallInteger('frequency');
                        $table->smallInteger('max_upload');
                        $table->smallInteger('max_broadcast');
                        $table->smallInteger('max_user');
                        $table->tinyInteger('is_active');
                        $table->text('description');
                        $table->timestamps();
                    });
            }
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('plan');
		}
	}
