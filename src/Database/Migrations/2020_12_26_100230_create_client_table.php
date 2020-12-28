<?php
	
	use Illuminate\Support\Facades\Schema;
	use Jenssegers\Mongodb\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateClientTable extends Migration {
		
		protected $connection = 'sulradio_mongo';
		
		public function up() {
			Schema::connection($this->connection)
				->table('client', function(Blueprint $collection) {
					$collection->background(["name"]);
					$collection->background(["last_name"]);
					$collection->background(["document_type"]);
					$collection->background(["document"]);
					$collection->background(["email"]);
					$collection->background(["cellphone"]);
					$collection->background(["telephone"]);
					$collection->background(["validated_at"]);
					$collection->background(["is_active"]);
					
					$collection->background(["street"]);
					$collection->background(["address_number"]);
					$collection->background(["complement"]);
					$collection->background(["city"]);
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('client');
		}
	}
