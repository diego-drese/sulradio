<?php

namespace Oka6\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;
use Oka6\SulRadio\Models\DocumentFolder;


class DocumentFolderTableSeed extends Seeder {
	public function run() {
		if(!DocumentFolder::count()){
			$list = [
				"Acordo",
				
			];
			
			foreach ($list as $value){
				DocumentFolder::create([
					'name'=> $value,
					'description'=> '',
					'is_active'=> '1',
				]);
			}
		}
	}
}
