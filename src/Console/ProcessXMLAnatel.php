<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Oka6\Admin\Library\MongoUtils;
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
	
	function stripAccents($stripAccents){
		return strtr($stripAccents,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function xml2array($array, $out = array()) {
		if(isset($array['@attributes'])){
			$newArray = $array['@attributes'];
			unset($array['@attributes']);
			$array = array_merge($newArray, $array);
		}
		foreach ($array as $index => $node){
			$index  = str_replace('@','', iconv('UTF-8','ASCII//TRANSLIT', $index));
            $index = preg_replace('/[^a-z0-9_]/i', '', $index);
			$out[strtolower($index)] = (is_array($node)) ? $this->xml2array($node) : $node;
		}
		return $out;
	}
	
	public function handle() {
		
		$this->stacaoRd();
		$this->planoBasico();
		$this->documentosHistorico();
		
		unlink(storage_path('app/estrangeirosAM.xml'));
		unlink(storage_path('app/estrangeirosTVFM.xml'));
		unlink(storage_path('app/reservasAM.xml'));
		unlink(storage_path('app/reservasTVFM.xml'));
		
	}
	public function downloadFile($url, $fileName){
		$this->info("Download file[{$fileName}] from ".$url);
		$streamContext = stream_context_create(array(
			'http' => array(
				'method' => 'GET',
				'timeout' => 300
			)
		));
		Storage::disk('local')->put($fileName, file_get_contents($url, false, $streamContext));
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
	
	public function documentosHistorico(){
		$this->downloadFile('http://sistemas.anatel.gov.br/se/public/file/b/srd/documento_historicos.zip', 'documento_historicos.zip');
		$this->unzipFiles('documento_historicos.zip');
		$this->processDocumentosHistorico();
	}
	
	public function processDocumentosHistorico(){
		$file   = storage_path('app/documento_historicos.xml');
		$fd     = fopen($file, "r");
		$contents = fread ($fd, filesize($file));
		$contents = str_replace("'",'"', $contents);
		$contents = str_replace('entidade="',"entidade='", $contents);
		$contents = str_replace('" c',"' c", $contents);
		$contents = str_replace('/>',"#>", $contents);
		$contents = str_replace('/documento_rd>',"#documento_rd>", $contents);
		$contents = str_replace('/',"", $contents);
		$contents = str_replace('#>',"/>", $contents);
		$contents = str_replace('#documento_rd>',"/documento_rd>", $contents);
		file_put_contents($file, $contents);
		fclose($fd);
		
		
		$this->info("Process start file[{$file}]");
		$reader = new \XMLReader();
		$reader->open($file);
		$line=0;
		while ($reader->read()) {
			$line++;
			$nodeName = null;
			if ($reader->nodeType == \XMLReader::ELEMENT) {
				$nodeName = $reader->name;
			}
			
			if ($nodeName == 'row') {
				
				$element                    = new \SimpleXMLElement($reader->readOuterXML());
				$objJsonDocument            = json_encode($element);
				$arrOutput                  = $this->xml2array(json_decode($objJsonDocument, TRUE));
				if(!empty($arrOutput['tipodocumento'])){
					$idDocument = $arrOutput['id'].'-'.$arrOutput['numeroprocesso'].'-'.$arrOutput['numerodocumento'].'-'.$arrOutput['tipodocumento'];
					$arrOutput['id_document']   = $idDocument;
					$estacaoRd = EstacaoRd::where('documento_historico.id_document',  $idDocument)->first();
					if(!$estacaoRd){
						EstacaoRd::where('id',  $arrOutput['id'])->push('documento_historico', $arrOutput);
					}
				}
			}
		}
		$this->info("Process finished file[{$file}]");
		unlink($file);
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
				$checksum           = md5($objJsonDocument);
				$arrOutput          = $this->xml2array(json_decode($objJsonDocument, TRUE));
				$arrOutput['checksum_stacao_rd']        = $checksum;
				$arrOutput['checksum_stacao_rd_date']   = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
				$estacaoRd = EstacaoRd::where('fistel', $arrOutput['fistel'])->first();
				if(!$estacaoRd){
					$arrOutput['client_id'] = null;
				}
				if(!$estacaoRd || $estacaoRd->checksum_stacao_rd!=$checksum){
					EstacaoRd::where('fistel',  $arrOutput['fistel'])
						->update($arrOutput, ['upsert' => true]);
				}
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
				$element                = new \SimpleXMLElement($reader->readOuterXML());
				$objJsonDocument        = json_encode($element);
				$checksum               = md5($objJsonDocument);
				$arrOutput              = $this->xml2array(json_decode($objJsonDocument, TRUE));
				if(isset($arrOutput['fistel'])){
					if(isset($arrOutput['entidade'])){
						unset($arrOutput['entidade']);
					}
					
					$arrOutput['checksum_plano_basico_am']  = $checksum;
					$arrOutput['checksum_plano_basico_am_date']  = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
					$estacaoRd = EstacaoRd::where('fistel', $arrOutput['fistel'])->first();
					if(!$estacaoRd || $estacaoRd->checksum_basic_plain!=$checksum){
						EstacaoRd::where('fistel', $arrOutput['fistel'])
							->update($arrOutput, ['upsert' => true]);
					}
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
				$checksum           = md5($objJsonDocument);
				$arrOutput          = $this->xml2array(json_decode($objJsonDocument, TRUE));
				
				if(isset($arrOutput['fistel'])){
					if(isset($arrOutput['entidade'])){
						unset($arrOutput['entidade']);
					}
					
					$arrOutput['checksum_plano_basico_tv_fm']  = $checksum;
					$arrOutput['checksum_plano_basico_tv_fm_date']  = MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
					$estacaoRd = EstacaoRd::where('fistel', $arrOutput['fistel'])->first();
					if(!$estacaoRd || $estacaoRd->checksum_plano_basico_tv_fm!=$checksum){
						EstacaoRd::where('fistel', $arrOutput['fistel'])
							->update($arrOutput, ['upsert' => true]);
					}
				}
			}
		}
		$this->info("Process finished file[{$file}]");
		unlink($file);
	}
}

