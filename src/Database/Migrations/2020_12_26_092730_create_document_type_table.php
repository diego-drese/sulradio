<?php
	
	use Illuminate\Support\Facades\Schema;
	use Jenssegers\Mongodb\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateDocumentTypeTable extends Migration {
		
		protected $connection = 'sulradio_mongo';
		
		public function up() {
			Schema::connection($this->connection)
				->table('document_type', function(Blueprint $collection) {
					$collection->background(["name"]);
					$collection->background(["is_active"]);
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('document_type');
		}
	}
