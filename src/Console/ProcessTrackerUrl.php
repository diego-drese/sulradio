<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketUrlTracker;


class ProcessTrackerUrl extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessTrackerUrl';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process tracker url';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	public function processUrls($urls, $notify=true){
		foreach ($urls as $url){
			$url->last_tracker = date('Y-m-d H:i:s');
			$html = $this->parseDomain($url->url);
			if(!$html){
				$url->save();
				continue;
			}
			$hash = md5($html);
			if($hash!=$url->hash){
				$url->hash = $hash;
				$url->last_modify = date('Y-m-d H:i:s');
				$user   = User::getByIdStatic(-1);
				$commentText = 'Acompanhamento da URL <a href="'.$url->url.'">'.$url->url.'</a><br/>';
				$comment = TicketComment::create([
					'html'=>$commentText,
					'user_id'=>$user->id,
					'ticket_id'=>$url->ticket_id
				]);
				if($notify){
					/** Notifica todos os participantes */
					$ticket = Ticket::getById($url->ticket_id);
					TicketParticipant::notifyParticipants($ticket, $user,TicketNotification::TYPE_TRACKER_URL, $comment->id);
				}
			}
			$url->save();
		}
	}
	public function handle() {
		Log::info('ProcessTrackerUrl, start process');
		try {
			$urls = TicketUrlTracker::getNew();
			$this->processUrls($urls ,false);

			$urls = TicketUrlTracker::getToCheck();
			$this->processUrls($urls);
		}catch (\Exception $e){
			Mail::raw('Erro o executar command, File['.$e->getFile().'] Line['.$e->getLine().'] Exception['.$e->getMessage().']', function($message) {
				$message->subject('Sulradio:ProcessTrackerUrl');
				$emails = ['diego-neumann@hotmail.com'];
				foreach ($emails as $email) {
					$message->to($email);
				}
			});
		}
		Log::info('ProcessTrackerUrl, end process');
	}

	public function parseDomain($url){
        $url        = str_replace('sei.mcom', 'super.mcom', $url);
		$parse      = parse_url($url);
		$domain     = $parse['host'];
		$dom        = new \DOMDocument('1.0', 'UTF-8');
		$dom->recover = true;
		$dom->strictErrorChecking = false;
        $proxy = 'https://scraper.sulradio.com.br?url='.$url;
		@$dom->loadHTMLFile($proxy);
		switch ($domain){
			case 'sei.anatel.gov.br':
			case 'sei.mctic.gov.br':
			case 'sei.mcom.gov.br':
			case 'super.mcom.gov.br':
				$html = null;
				$tblDocumentos = $dom->saveXML($dom->getElementById('tblDocumentos'));
				if (str_contains($tblDocumentos, 'table id="tblDocumentos"')) {
					$tblDocumentos =preg_replace("/<\/?a( [^>]*)?>/i", "", $tblDocumentos);
					$tblDocumentos = preg_replace("/<img src=.*?>(.*?)/","", $tblDocumentos);
					$tblDocumentos = preg_replace("/<input type=.*?>(.*?)/","", $tblDocumentos);
					$html=$tblDocumentos;
				}

				$tblHistorico= $dom->saveXML($dom->getElementById('tblHistorico'));
				if (str_contains($tblHistorico, 'table id="tblHistorico"')) {
					$tblHistorico = preg_replace("/<a href=.*?>(.*?)<\/a>/","", $tblHistorico);
					$tblHistorico = preg_replace("/<img src=.*?>(.*?)/","", $tblHistorico);
					$tblHistorico = preg_replace("/<input type=.*?>(.*?)/","", $tblHistorico);
					$html.=$tblHistorico;
				}
				Log::info('ProcessTrackerUrl parseDomain', ['url'=>$url]);
				return $html;
			break;
			default;
				return $dom->saveXML($dom->getElementsByTagName('body'));
			break;
		}

	}
	
	
}

