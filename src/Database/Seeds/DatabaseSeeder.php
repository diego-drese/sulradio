<?php

namespace Oka6\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;
use Oka6\SulRadio\Models\DocumentFolder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call(ResourcesTableSeed::class);
		$this->call(DocumentTypeTableSeed::class);
		$this->call(DocumentFolderTableSeed::class);
		$this->call(StatesTableSeeder::class);
		$this->call(CitiesTableSeeder::class);
	}
}
