<?php

namespace MachadoTi\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(ResourcesTableSeed::class);
    }
}
