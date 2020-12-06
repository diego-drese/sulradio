<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Oka6\SulRadio\Models\EstacaoRd;

class ProcessXMLAnatel extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessXMLAnatel';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process files from http://sistemas.anatel.gov.br files estacao_rd.zip, PlanoBasico.zip,  documento_historicos.zip';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function xml2array($array, $out = array()) {
		foreach ($array as $index => $node){
			$index= str_replace('@','',$index);
			$out[strtolower($index)] = (is_array($node)) ? $this->xml2array($node) : $node;
		}
		return $out;
	}
	
	public function handle() {
		$this->stacaoRd();
		$this->planoBasico();
		
		unlink(storage_path('app/estrangeirosAM.xml'));
		unlink(storage_path('app/estrangeirosTVFM.xml'));
		unlink(storage_path('app/reservasAM.xml'));
		unlink(storage_path('app/reservasTVFM.xml'));
		
	}
	public function downloadFile($url, $fileName){
		$this->info("Download file[{$fileName}] from ".$url);
		Storage::disk('local')->put($fileName, file_get_contents($url));
	}
	public function unzipFiles($fileNameZip){
		$file = storage_path("app/{$fileNameZip}");
		$this->info("Unzip file[{$file}]");
		$zip = new \ZipArchive();
		if ($zip->open($file) === TRUE) {
			$zip->extractTo(storage_path('app/'));
			$zip->close();
		}
		unlink($file);
	}
	
	public function stacaoRd(){
		$this->downloadFile('http://sistemas.anatel.gov.br/se/public/file/b/srd/estacao_rd.zip', 'estacao_rd.zip');
		$this->unzipFiles('estacao_rd.zip');
		$this->processStacaoRd();
	
	}
	public function processStacaoRd(){
		$file = storage_path('app/estacao_rd.xml');
		$this->info("Process start file[{$file}]");
		$reader = new \XMLReader();
		$reader->open($file);
		while ($reader->read()) {
			if ($reader->nodeType == \XMLReader::ELEMENT) {
				$nodeName = $reader->name;
			}
			
			if ($nodeName == 'row') {
				$element            = new \SimpleXMLElement($reader->readOuterXML());
				$objJsonDocument    = json_encode($element);
				$arrOutput          = $this->xml2array(json_decode($objJsonDocument, TRUE));
				$arrayAttr          = $arrOutput['attributes'];
				unset($arrOutput['attributes']);
				$arraySave= array_merge($arrayAttr, $arrOutput);
				EstacaoRd::where('fistel', $arraySave['fistel'])->update($arraySave, ['upsert' => true]);
			}
		}
		$this->info("Process finished file[{$file}]");
		unlink($file);
	}
	
	public function planoBasico(){
		$this->downloadFile('http://sistemas.anatel.gov.br/se/public/file/b/srd/PlanoBasico.zip', 'PlanoBasico.zip');
		$this->unzipFiles('PlanoBasico.zip');
		$this->processPlanoBasicoAm();
		$this->processPlanoBasicoTVFM();
	}
	
	public function processPlanoBasicoAm(){
		$file = storage_path('app/plano_basicoAM.xml');
		$this->info("Process start file[{$file}]");
		$reader = new \XMLReader();
		$reader->open($file);
		while ($reader->read()) {
			if ($reader->nodeType == \XMLReader::ELEMENT) {
				$nodeName = $reader->name;
			}
			if ($nodeName == 'row') {
				$element            = new \SimpleXMLElement($reader->readOuterXML());
				$objJsonDocument    = json_encode($element);
				$arrOutput          = $this->xml2array(json_decode($objJsonDocument, TRUE));
				if(isset($arrOutput['attributes'])){
					EstacaoRd::where('fistel', $arrOutput['attributes']['fistel'])->update($arrOutput['attributes'], ['upsert' => true]);
				}
			}
		}
		$this->info("Process finished file[{$file}]");
		unlink($file);
	}
	
	public function processPlanoBasicoTVFM(){
		$file = storage_path('app/plano_basicoTVFM.xml');
		$this->info("Process start file[{$file}]");
		$reader = new \XMLReader();
		$reader->open($file);
		while ($reader->read()) {
			if ($reader->nodeType == \XMLReader::ELEMENT) {
				$nodeName = $reader->name;
			}
			if ($nodeName == 'row') {
				$element            = new \SimpleXMLElement($reader->readOuterXML());
				$objJsonDocument    = json_encode($element);
				$arrOutput          = $this->xml2array(json_decode($objJsonDocument, TRUE));
				if(isset($arrOutput['attributes'])){
					EstacaoRd::where('fistel', $arrOutput['attributes']['fistel'])->update($arrOutput['attributes'], ['upsert' => true]);
				}
			}
		}
		$this->info("Process finished file[{$file}]");
		unlink($file);
	}
}

