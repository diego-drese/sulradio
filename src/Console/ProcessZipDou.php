<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Models\EstacaoRd;

class ProcessZipDou extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessZipDou';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process files .zip from https://inlabs.in.gov.br ';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	public function stripAccents($stripAccents){
		return strtr($stripAccents,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
	public function getPathZip($file=''){
		if(!is_dir(storage_path('app/dou-zip'))){
			File::makeDirectory(storage_path('app/dou-zip'));
		}
		return storage_path('app/dou-zip/'.$file);
	}
	public function getPathXml($file=''){
		if(!is_dir(storage_path('app/dou-xml'))){
			File::makeDirectory(storage_path('app/dou-xml'));
		}
		return storage_path('app/dou-xml/'.$file);
	}
	
	
	
	public function handle() {
		$this->unzipFiles();
		$files = array_diff(scandir($this->getPathXml()), ['..', '.']);
		foreach ($files as $file){
			try {
				$parseFile  = simplexml_load_file($this->getPathXml($file),'SimpleXMLElement', LIBXML_NOCDATA);
				$attributes = (array)$parseFile->article->attributes();
				$attributes = $attributes['@attributes'];
				$body       = (array)$parseFile->article->body;
			}catch (\Exception $e){
				$this->error('Error parse file['.$file.'] e['.$e->getMessage().']');
			}
			
			unlink($this->getPathXml($file));
		}
	}
	
	
}

