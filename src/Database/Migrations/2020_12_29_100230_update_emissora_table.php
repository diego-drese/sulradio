<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	class UpdateEmissoraTable extends Migration {
		
		protected $connection = 'sulradio';
		
		public function up() {
			Schema::connection($this->connection)
				->table('emissora', function( $collection) {
					$collection->integer("client_id")->nullable()->index();
					$collection->index("razao_social");
					$collection->index("nome_fantasia");
				});
		}
		
		public function down() {
			Schema::connection($this->connection)->table('emissora', function (Blueprint $table) {
				$table->dropColumn(['client_id']);
				$table->dropIndex(['razao_social']);
				$table->dropIndex(['nome_fantasia']);
			});
		}
	}
