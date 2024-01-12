<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateDocumentDate extends Migration {
	
	protected $connection = 'sulradio';
	
	public function up() {
        if(Schema::connection($this->connection)->hasTable('document')) {
            Schema::connection($this->connection)
                ->table('document', function (Blueprint $table) {
                    $table->timestamp('date_document')->nullable()->index();
                });
        }
	}
	
	public function down() {
        if(Schema::connection($this->connection)->hasTable('document')) {
            Schema::connection($this->connection)->table('document', function (Blueprint $table) {
                $table->dropColumn('date_document');
            });
        }
	}
}
