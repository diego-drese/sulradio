<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateClientTable extends Migration {
		
		protected $connection = 'sulradio';
		
		public function up() {
            if(!Schema::connection($this->connection)->hasTable('client')) {
                Schema::connection($this->connection)
                    ->create('client', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name', 191);
                        $table->string('company_name', 191);
                        $table->string('email', 191);
                        $table->string('document_type', 191);
                        $table->string('document', 191);
                        $table->string('cellphone', 191)->nullable();
                        $table->string('telephone', 191)->nullable();
                        $table->string('zip_code', 191)->nullable();
                        $table->string('street', 191)->nullable();
                        $table->string('neighborhood', 191)->nullable();
                        $table->string('address_number', 191)->nullable();
                        $table->string('complement', 191)->nullable();
                        $table->integer('city_id')->nullable();
                        $table->integer('plan_id');
                        $table->tinyInteger('is_active');
                        $table->text('description')->nullable();
                        $table->text('broadcast');
                        $table->text('users')->nullable();
                        $table->smallInteger('uploads_gb')->default(0);
                        $table->timestamp('validated_at');
                        $table->timestamps();
                    });
            }
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('client');
		}
	}
