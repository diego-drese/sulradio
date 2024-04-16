<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Mail\TicketComment;
use Oka6\SulRadio\Mail\TicketCreate;
use Oka6\SulRadio\Mail\TicketTransfer;
use Oka6\SulRadio\Mail\TicketUpdate;
use Oka6\SulRadio\Mail\TicketCommentFromClient;
use Oka6\SulRadio\Models\Emissora;
use Oka6\SulRadio\Models\EstacaoRd;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketNotification;


class AdjustFistelEmissoras extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:AdjustFistelEmissoras';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Adjust fistel in table emissoras';
	
	
	protected $emailFrom = null;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public function handle() {
		Log::info('AdjustFistelEmissoras, start process');
		$emissoras = Emissora::whereNull('fistel')
			->withServico()
			->whereNull('url_mosaico')
			->whereNull('url_seacco')
			->whereNull('url_cnpj')
			->where('cnpj','!=', '')
			->limit(1000)
			->get();
		$urlMosaico = Config::get('sulradio.url_mosaico');
        $urlSeacco = Config::get('sulradio.url_seacco');
        $urlCNPJ = Config::get('sulradio.url_cnpj');

		foreach ($emissoras as $emissora){
			$cnpjNumber = preg_replace('/[^0-9]/', '', $emissora->cnpj);
			$estacaoRd = EstacaoRd::getByCnpj($cnpjNumber);
			if($estacaoRd){
				$urlMosaicoParsed = str_replace('{ID}', $estacaoRd->id, $urlMosaico);
				$urlMosaicoParsed = str_replace('{STATE}', $emissora->desc_servico, $urlMosaicoParsed);

				$urlSeaccoParsed = str_replace('{NOME}', substr($emissora->razao_social, 0, 20), $urlSeacco);
				$urlSeaccoParsed = str_replace('{CNPJ}', $cnpjNumber, $urlSeaccoParsed);

				$urlCNPJParsed  = str_replace('{CNPJ}', $cnpjNumber, $urlCNPJ);

				$emissora->fistel = $estacaoRd->fistel;
				$emissora->url_mosaico = $urlMosaicoParsed;
				$emissora->url_seacco = $urlSeaccoParsed;
				$emissora->url_cnpj = $urlCNPJParsed;
				$emissora->save();
			}else{
				Log::info('AdjustFistelEmissoras estacaoRd not found', ['emissora'=>$emissora]);
			}
		}

	}
}

