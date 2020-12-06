<?php
	
	use Illuminate\Support\Facades\Schema;
	use Jenssegers\Mongodb\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateEstacaoRdTable extends Migration {
		
		protected $connection = 'sulradio_mongo';
		
		public function up() {
			Schema::connection($this->connection)
				->table('estacao_rd', function(Blueprint $collection) {
					$collection->background(["id"]);
					$collection->background(["fistel"]); // pay or receive
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('estacao_rd');
		}
	}
