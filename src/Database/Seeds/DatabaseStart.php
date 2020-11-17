<?php

namespace Oka6\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Oka6\Admin\Models\User;

class DatabaseStart extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $hasUserCreated = User::hasUserCreated();
        if ($hasUserCreated) {
            $this->command->line('Seed already started, ignoring seed from admin');
            $this->command->line('Run Config:cache');
            Artisan::call('config:cache');

            $this->command->line('Run SulRadio:DataBaseStart');
            $this->call(\Oka6\SulRadio\Database\Seeds\DatabaseSeeder::class);

            $this->command->line('Run Oka6:AdminRoutes');
            Artisan::call('Oka6:AdminRoutes');

            $this->command->line('Run vendor:publish');
            Artisan::call('vendor:publish --tag=0 --force');
            return;
        }
        $this->command->line('Run Config:cache');
        Artisan::call('config:cache');
	    
        $this->command->line('Run Admin:DatabaseSeeder');
        $this->call(\Oka6\Admin\Database\Seeds\DatabaseSeeder::class);

        $this->command->line('Run SulRadio:DataBaseStart');
        $this->call(\Oka6\SulRadio\Database\Seeds\DatabaseSeeder::class);

        $this->command->line('Run Oka6:AdminRoutes');
        Artisan::call('Oka6:AdminRoutes');

        $this->command->line('Run vendor:publish');
        Artisan::call('vendor:publish --tag=0 --force');
    }
}
