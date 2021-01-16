<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Models\Dou;
use Oka6\SulRadio\Models\DouCategory;
use Oka6\SulRadio\Models\DouType;

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
	protected $description  = 'Process files .zip from https://inlabs.in.gov.br';
	protected $folderZip    = 'dou-zip/';
	protected $folderXml    = 'dou-xml/';
	protected $retryDownload= 0;
	protected $maxRetryDownload= 4;
	
	protected $dadosGovUrls = [
		'2020-01-secao1' => 'http://www.in.gov.br/documents/49035712/241907428/S01012020.zip/f9446353-44bc-7c4d-bf0b-c48e9359080c?t=1581017828203',
		'2020-01-secao2' => 'http://www.in.gov.br/documents/49035712/241907428/S02012020.zip/83745fe5-685e-1639-77d7-2e5a99673c90?t=1581017832367',
		'2020-01-secao3' => 'http://www.in.gov.br/documents/49035712/241907428/S03012020.zip/32a590f6-a39c-ed03-f078-eda810c0bf21?t=1581017843677',
		
		'2020-02-secao1' => 'http://www.in.gov.br/documents/49035712/246594191/S01022020.zip/35aab88b-3fbf-6730-ec95-17d75026718e?t=1583505277838',
		'2020-02-secao2' => 'http://www.in.gov.br/documents/49035712/246594191/S02022020.zip/56d8245c-f96b-f29a-6a4c-d2e98050be40?t=1583505282320',
		'2020-02-secao3' => 'http://www.in.gov.br/documents/49035712/246594191/S03022020.zip/c33b6c9b-8007-f123-32e0-4eaafc338a56?t=1583505297783',
		
		'2020-03-secao1' => 'http://www.in.gov.br/documents/49035712/251350156/S01032020.zip/7edb87ed-674a-5504-d297-4dc1397bb16a?t=1586195007009',
		'2020-03-secao2' => 'http://www.in.gov.br/documents/49035712/251350156/S02032020.zip/a8cf9955-af53-6f4d-1266-a095b709cd3f?t=1586195012242',
		'2020-03-secao3' => 'http://www.in.gov.br/documents/49035712/251350156/S03032020.zip/16c6fe3f-f0a8-c395-8e5b-50ee0c338bef?t=1586195015088',
		
		'2020-04-secao1' => 'http://www.in.gov.br/documents/49035712/255682696/S01042020.zip/26f0389d-b8e3-b5c7-9534-433dea46fc04?t=1588865006830',
		'2020-04-secao2' => 'http://www.in.gov.br/documents/49035712/255682696/S02042020.zip/3dfbf2e0-d297-6625-04d0-cc2ef1c6231c?t=1588865015808',
		'2020-04-secao3' => 'http://www.in.gov.br/documents/49035712/255682696/S03042020.zip/02490c6d-2f94-c93e-da29-ce31a779ccee?t=1588865028967',
		
		'2020-05-secao1' => 'http://www.in.gov.br/documents/49035712/260390212/S01052020.zip/9404cc50-f3a8-2716-16f8-bfd7c2b6ceb7?t=1591361677034',
		'2020-05-secao2' => 'http://www.in.gov.br/documents/49035712/260390212/S02052020.zip/c955bd3f-a2a1-4d70-8d8b-1a4712cfe8a2?t=1591361680206',
		'2020-05-secao3' => 'http://www.in.gov.br/documents/49035712/260390212/S03052020.zip/68375d28-be6e-5814-dcb7-12971d7f2e97?t=1591361690362',
		
		'2020-06-secao1' => 'http://www.in.gov.br/documents/49035712/264746817/S01062020.zip/f51c0eb0-72c8-4002-2284-e364fa063bb6?t=1593721375567',
		'2020-06-secao2' => 'http://www.in.gov.br/documents/49035712/264746817/S02062020.zip/3fc6b17b-3c01-b71c-034b-1767e9e95eb8?t=1593721379207',
		'2020-06-secao3' => 'http://www.in.gov.br/documents/49035712/264746817/S03062020.zip/8ab1b3c6-2767-5fea-4bf4-5a8e58ff3bfd?t=1593721395844',
		
		'2020-07-secao1' => 'https://www.in.gov.br/documents/49035712/271056274/S01072020.zip/f8272d66-a11f-315e-d537-285dbe77f516?t=1596831158845',
		'2020-07-secao2' => 'https://www.in.gov.br/documents/49035712/271056274/S01072020.zip/f8272d66-a11f-315e-d537-285dbe77f516?t=1596831158845',
		'2020-07-secao3' => 'https://www.in.gov.br/documents/49035712/271056274/S03072020.zip/94f26b1c-6b5c-1707-b4dd-b57bf5de3034?t=1596831754591',
		
		'2020-08-secao1' => 'https://www.in.gov.br/documents/49035712/275734331/S01082020.zip/6e2651df-bb79-6f17-702d-304205937335?t=1599145199544',
		'2020-08-secao2' => 'https://www.in.gov.br/documents/49035712/275734331/S02082020.zip/baf8e9b5-af02-88f9-9eda-6e549de94fa0?t=1599145203962',
		'2020-08-secao3' => 'https://www.in.gov.br/documents/49035712/275734331/S03082020.zip/adad9889-4018-2a07-47f5-63ca81ffc850?t=1599145216558',
		
		'2020-09-secao1' => 'https://www.in.gov.br/documents/49035712/281617409/S01092020.zip/a4cb83cc-3583-b2a4-8f24-0db9afa3b781?t=1602091684501',
		'2020-09-secao2' => 'https://www.in.gov.br/documents/49035712/281617409/S02092020.zip/89953e34-db78-0450-34c2-1ec968255082?t=1602091692334',
		'2020-09-secao3' => 'https://www.in.gov.br/documents/49035712/281617409/S03092020.zip/b072622c-9ea7-01b9-be23-f1f23fbbfa5c?t=1602091716978',
		
		'2020-10-secao1' => 'https://www.in.gov.br/documents/49035712/286719135/S01102020.zip/a32d8b06-7589-8e63-3d81-2b65b11ae5c4?t=1604943502740',
		'2020-10-secao2' => 'https://www.in.gov.br/documents/49035712/286719135/S02102020.zip/cfdb923b-b8ed-9275-c378-f0901004f041?t=1604943521036',
		'2020-10-secao3' => 'https://www.in.gov.br/documents/49035712/286719135/S03102020.zip/6b2bda2e-bd43-1a7e-ed75-fc6fa5cefdfe?t=1604943610607',
		
		'2020-11-secao1' => 'https://www.in.gov.br/documents/49035712/292952260/S01112020.zip/f8d52932-8776-40e6-615d-26ec05329710?t=1607436060539',
		'2020-11-secao2' => 'https://www.in.gov.br/documents/49035712/292952260/S02112020.zip/655cf468-7aae-c199-f887-70e5fb9bda90?t=1607436072321',
		'2020-11-secao3' => 'https://www.in.gov.br/49035712/292952260/S03112020.zip/195d0f89-88a2-363d-254c-ea7d4f95d1cd?t=1607436119019',
		
		'2020-12-secao1' => 'https://www.in.gov.br/documents/49035712/298262853/S01122020.zip/d6e04676-41e1-fd3f-3e7b-c1e5c05c501f?t=1610387118057',
		'2020-12-secao2' => 'https://www.in.gov.br/documents/49035712/298262853/S02122020.zip/61b86002-f9ea-6a51-13e8-5aaf1f9b13d0?t=1610387137947',
		'2020-12-secao3' => 'https://www.in.gov.br/documents/49035712/298262853/S02122020.zip/61b86002-f9ea-6a51-13e8-5aaf1f9b13d0?t=1610387137947',
	];
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	public function getPathZip($file=''){
		if(!is_dir(storage_path('app/'.$this->folderZip))){
			File::makeDirectory(storage_path('app/'.$this->folderZip));
		}
		return storage_path('app/'.$this->folderZip.$file);
	}
	public function getPathXml($file=''){
		if(!is_dir(storage_path('app/'.$this->folderXml))){
			File::makeDirectory(storage_path('app/'.$this->folderXml));
		}
		return storage_path('app/'.$this->folderXml.$file);
	}
	
	public function firstInsert(){
		if(!Dou::count()){
			$this->info("firstInsert, dou empty");
			foreach ($this->dadosGovUrls as $key=>$url){
				$this->downloadFile($url, $this->folderZip.$key.'.zip');
				$this->unzipFiles();
				$this->processXml();
			}
		}
	}
	function limit_text($text, $limit) {
		if (str_word_count($text, 0) > $limit) {
			$words = str_word_count($text, 2);
			$pos   = array_keys($words);
			$text  = substr($text, 0, $pos[$limit]) . '...';
		}
		return $text;
	}
	
	public function handle() {
		$this->firstInsert();
		$this->unzipFiles();
		$this->processXml();
	}
	public function downloadFile($url, $fileName){
		try {
			$this->info("downloadFile, file[{$fileName}] from[".$url."]");
			Storage::disk('local')->put($fileName, file_get_contents($url));
			$this->retryDownload = 0;
		}catch (\Exception $e){
			$this->retryDownload++;
			if($this->retryDownload<$this->maxRetryDownload){
				$this->info('downloadFile, retry ['.$this->retryDownload.'] exception ['.$e->getMessage().']');
				$this->downloadFile($url, $fileName);
			}else{
				$this->retryDownload = 0;
				$this->error('downloadFile, max retry['.$this->maxRetryDownload.'] reached exception ['.$e->getMessage().']');
			}
		}
		
	}
	
	public function processXml(){
		$files = array_diff(scandir($this->getPathXml()), ['..', '.']);
		$this->info("processXml, start process[".count($files)."] files");
		foreach ($files as $file){
			try {
				$parseFile      = simplexml_load_file($this->getPathXml($file),'SimpleXMLElement', LIBXML_NOCDATA);
				$attributes     = (array)$parseFile->article->attributes();
				$attributes     = $attributes['@attributes'];
				$body           = (array)$parseFile->article->body;
				
				$douType        = DouType::createOrUpdate($attributes['artType']);
				$pubDate        = Helper::convertDateBrToMysql($attributes['pubDate']);
				$douCategories  = DouCategory::createOrUpdate($attributes['artCategory']);
				$text           = is_string($body['Texto']) ?  $body['Texto'] : '';
				$textStart      = strip_tags(str_replace('>', '> ', $text));
				$textStart      = $this->limit_text($textStart, 30);
				
				$dataSave       = [
					'id'=>$attributes['id'],
					'name'=>$attributes['name'],
					'id_oficio'=>$attributes['idOficio'],
					'pub_name'=>$attributes['pubName'],
					'type_id'=>$douType->id,
					'type_name'=>$douType->name,
					'date'=>MongoUtils::convertDatePhpToMongo($pubDate),
					'categories'=>$douCategories->toArray(),
					'page_number'=>$attributes['numberPage'],
					'edition_number'=>$attributes['editionNumber'],
					'id_materia'=>$attributes['idMateria'],
					'ementa'=> is_string($body['Ementa']) ? $body['Ementa'] : null,
					'titulo'=> is_string($body['Titulo']) ? $body['Titulo'] : null,
					'identifica'=>is_string($body['Identifica']) ? $body['Identifica'] : null,
					'data'=> is_string($body['Data']) ? $body['Data'] : null,
					'sub_titulo'=> is_string($body['SubTitulo']) ? $body['SubTitulo']: null,
					'text_start'=> $textStart,
					'text'=> $text,
				];
				Dou::where('id', $attributes['id'])->update($dataSave, ['upsert' => true]);
			}catch (\Exception $e){
				$this->error('Error parse file['.$file.'] e['.$e->getMessage().']');
			}
			unlink($this->getPathXml($file));
		}
	}
	
	public function unzipFiles(){
		$files = array_diff(scandir($this->getPathZip()), ['..', '.']);
		$this->info("unzipFiles, files[".implode(',', $files)."]");
		foreach ($files as $file){
			$zip = new \ZipArchive();
			if ($zip->open($this->getPathZip($file)) === TRUE) {
				for($i = 0; $i < $zip->numFiles; $i++) {
					$filename = $zip->getNameIndex($i);
					$fileinfo = pathinfo($filename);
					copy("zip://".$this->getPathZip($file)."#".$filename, $this->getPathXml($fileinfo['basename']));
				}
				$zip->close();
			}
			unlink($this->getPathZip($file));
			$this->info("unzipFiles, extract[{$file}] from[".$this->getPathXml()."]");
		}
	}
	
	
}

