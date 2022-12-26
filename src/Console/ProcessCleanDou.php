<?php

namespace Oka6\SulRadio\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Models\Dou;

class ProcessCleanDou extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessCleanDou';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process clean dou less 3 months';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	

	
	public function handle() {
        $limitDelete = Carbon::now()->subMonths(3);
		$count = Dou::where('created_at', '<=', MongoUtils::convertDatePhpToMongo($limitDelete))
            ->where('ato_id', 'exists', false)
            ->count();
        $this->info("ProcessCleanDou, delete less[".$limitDelete->format('Y-m-d')."], total[{$count}]");
	}

}

