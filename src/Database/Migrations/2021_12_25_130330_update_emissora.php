<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	class UpdateEmissora extends Migration {
		
		protected $connection = 'sulradio';
		
		public function up() {
            if(Schema::connection($this->connection)->hasTable('emissora')) {
                Schema::connection($this->connection)
                    ->table('emissora', function ($collection) {
                        $collection->string("fistel", 50)->nullable()->index();
                        $collection->string("url_mosaico", 191)->nullable();
                        $collection->string("url_seacco", 191)->nullable();
                        $collection->string("url_cnpj", 191)->nullable();
                    });
            }
		}
		
		public function down() {
            if(Schema::connection($this->connection)->hasTable('emissora')) {
                Schema::connection($this->connection)->table('emissora', function (Blueprint $table) {
                    $table->dropColumn(['fistel', 'url_mosaico', 'url_seacco', 'url_cnpj']);
                });
            }
		}
	}
