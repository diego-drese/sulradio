<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateDocumentHistoricTable extends Migration {
		protected $connection = 'sulradio';
		public function up() {
            if(!Schema::connection($this->connection)->hasTable('document_historic')) {
                Schema::connection($this->connection)
                    ->create('document_historic', function (Blueprint $table) {
                        $table->bigIncrements('id');
                        $table->integer('user_id')->index();
                        $table->integer('document_id')->index();
                        $table->enum('action', ['created', 'updated', 'downloaded', 'undefined']);
                        $table->timestamps();
                        $table->index('created_at');
                    });
            }
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('document_historic');
		}
	}
