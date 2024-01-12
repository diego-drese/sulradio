<?php
	
	use Illuminate\Support\Facades\Schema;
	use Jenssegers\Mongodb\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateEstacaoRdTable extends Migration {
		
		protected $connection = 'sulradio_mongo';
		
		public function up() {
            if(!Schema::connection($this->connection)->hasTable('estacao_rd')) {
                Schema::connection($this->connection)
                    ->table('estacao_rd', function(Blueprint $collection) {
                        $collection->background(["id"]);
                        $collection->background(["checksum_stacao_rd"]);
                        $collection->background(["checksum_stacao_rd_date"]);
                        $collection->background(["checksum_plano_basico_am"]);
                        $collection->background(["checksum_plano_basico_am_date"]);
                        $collection->background(["checksum_plano_basico_tv_fm"]);
                        $collection->background(["checksum_plano_basico_tv_fm_date"]);
                        $collection->background(["checksum"]);
                        $collection->background(["documento_historico.id_document"]);
                        $collection->background(["fistel"]);
                        $collection->background(["updated_at"]);
                    });
            }
		}
		
		public function down() {
			Schema::connection($this->connection)->dropIfExists('estacao_rd');
		}
	}
