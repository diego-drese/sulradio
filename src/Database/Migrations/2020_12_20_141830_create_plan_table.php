<?php
	
	use Illuminate\Support\Facades\Schema;
	use Jenssegers\Mongodb\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreatePlanTable extends Migration {
		
		protected $connection = 'sulradio_mongo';
		
		public function up() {
			Schema::connection($this->connection)
				->table('plan', function(Blueprint $collection) {
					$collection->background(["name"]);
					$collection->background(["max_broadcast"]);
					$collection->background(["max_upload"]);
					$collection->background(["max_user"]);
					$collection->background(["updated_at"]);
					$collection->background(["is_active"]);
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('plan');
		}
	}
