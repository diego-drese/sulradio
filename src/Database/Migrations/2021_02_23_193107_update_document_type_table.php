<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDocumentTypeTable extends Migration {
	
	protected $connection = 'sulradio';
	
	public function up() {
        if(Schema::connection($this->connection)->hasTable('document_type')) {
            Schema::connection($this->connection)
                ->table('document_type', function (Blueprint $table) {
                    $table->enum('goal', ['Cliente', 'Engenharia', 'Jurídico', 'Administrativo'])->default('Cliente')->index();
                });
        }
	}
	
	public function down() {
        if(Schema::connection($this->connection)->hasTable('document_type')) {
            Schema::connection($this->connection)->table('document_type', function (Blueprint $table) {
                $table->dropColumn('goal');
            });
        }
	}
}
