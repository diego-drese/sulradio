<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateDocumentFolderTable extends Migration {
		protected $connection = 'sulradio';
		public function up() {
            if(!Schema::connection($this->connection)->hasTable('document_folder')) {
                Schema::connection($this->connection)
                    ->create('document_folder', function (Blueprint $table) {
                        $table->increments('id');
                        $table->string('name', 191);
                        $table->tinyInteger('is_active');
                        $table->text('description')->nullable();
                        $table->timestamps();
                    });
            }
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('document_folder');
		}
	}
