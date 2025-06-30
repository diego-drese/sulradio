<?php

namespace Oka6\SulRadio\Console;

use Carbon\Carbon;
use DateInterval;
use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketComment;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketUrlTracker;
use DateTime;


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
            $lastModify = new DateTime($url->last_modify, new DateTimeZone('UTC'));
            $maxDate = $this->parseDomain($url->url);
			if(!$maxDate){
				$url->save();
				continue;
			}
            if($maxDate && $lastModify->getTimestamp() < $maxDate->getTimestamp()){
                $url->hash          = md5($url->url);
                $url->last_modify   = $maxDate;
                $diffInSeconds      = $maxDate->getTimestamp() - $lastModify->getTimestamp();
                $diffInHours        = $diffInSeconds / 3600;
                Log::info('ProcessTrackerUrl, debug', ['url'=>$url->url, 'last_tracker'=>$url->last_tracker, 'lastModify'=>$lastModify, 'max_date'=>$maxDate, 'id'=>$url->id, 'diffInHours'=>$diffInHours]);
                if ($diffInHours > 3) {
                    Log::info('ProcessTrackerUrl, process changed', ['url'=>$url->url, 'last_tracker'=>$url->last_tracker, 'lastModify'=>$lastModify, 'max_date'=>$maxDate, 'id'=>$url->id, 'diff'=>$diffInHours]);
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
            }elseif($maxDate && $lastModify->getTimestamp() > $maxDate->getTimestamp()){
                Log::info('ProcessTrackerUrl, adjust database', ['url'=>$url->url, 'last_tracker'=>$url->last_tracker, 'lastModify'=>$lastModify, 'max_date'=>$maxDate, 'id'=>$url->id]);
                $url->last_modify = $maxDate;
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
		$dom        = new \DOMDocument('1.0', 'UTF-8');
		$dom->recover = true;
		$dom->strictErrorChecking = false;
		@$dom->loadHTMLFile($url);
        return $this->getMaxRelevantDateFromDom($dom);
	}

    function getMaxRelevantDateFromDom(\DOMDocument $dom) {
        $xpath = new \DOMXPath($dom);
        $maxDate = null;


        // 1. Pega datas dos <tr class="andamentoConcluido">
        foreach ($xpath->query('//tr[contains(@class, "andamentoAberto")]') as $tr) {
            $tds = $tr->getElementsByTagName('td');
            if ($tds->length > 0) {
                $dateText = trim($tds->item(0)->nodeValue);
                $date = DateTime::createFromFormat('d/m/Y H:i', $dateText, new DateTimeZone('America/Sao_Paulo'))->setTimezone(new DateTimeZone('UTC'));
                if ($date && ($maxDate === null || $date > $maxDate)) {
                    $maxDate = $date;
                }
            }
        }

        foreach ($xpath->query('//tr[contains(@class, "andamentoConcluido")]') as $tr) {
            $tds = $tr->getElementsByTagName('td');
            if ($tds->length > 0) {
                $dateText = trim($tds->item(0)->nodeValue);
                $date = DateTime::createFromFormat('d/m/Y H:i', $dateText, new DateTimeZone('America/Sao_Paulo'))->setTimezone(new DateTimeZone('UTC'));
                if ($date && ($maxDate === null || $date > $maxDate)) {
                    $maxDate = $date;
                }
            }
        }
        if($maxDate === null ){
            // 2. Pega datas dos <tr class="infraTrClara"> (colunas 3 e 4)
            foreach ($xpath->query('//tr[contains(@class, "infraTrClara")]') as $tr) {
                $tds = $tr->getElementsByTagName('td');
                // Coluna 4 (índice 4)
                if ($tds->length > 4) {
                    $dateText = trim($tds->item(4)->nodeValue);
                    $date = DateTime::createFromFormat('d/m/Y', $dateText, new DateTimeZone('America/Sao_Paulo'))->setTimezone(new DateTimeZone('UTC'));
                    if ($date && ($maxDate === null || $date > $maxDate)) {
                        $maxDate = $date;
                    }
                }
            }

        }

        return $maxDate;
    }
	
}

