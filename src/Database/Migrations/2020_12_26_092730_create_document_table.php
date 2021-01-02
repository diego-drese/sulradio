<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateDocumentTable extends Migration {
		protected $connection = 'sulradio';
		public function up() {
			Schema::connection($this->connection)
				->create('document', function(Blueprint $table) {
					$table->increments('id');
					$table->string('name', 191);
					$table->string('description', 191)->nullable();
					$table->integer('document_type_id');
					$table->integer('version')->default(2);
					$table->integer('document_id')->default(0)->index();
					$table->integer('emissora_id');
					
					$table->string('file_name', 191);
					$table->string('file_type', 191);
					$table->string('file_preview', 191);
					$table->string('file_size', 191);
					
					$table->tinyInteger('status');
					$table->timestamp('validated')->nullable();
					$table->timestamps();
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('document');
		}
	}
