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
    public function processUrls($urls, $notify = true){
        $limitDate = Carbon::now()->subDays(2)->startOfDay();
        foreach ($urls as $url) {
            $url->last_tracker = now();
            $lastModify = Carbon::parse($url->last_modify ?? '1970-01-01');
            $maxDateRaw = $this->parseDomain($url->url);
            if (!$maxDateRaw) {
                $url->save();
                continue;
            }

            $maxDate = Carbon::parse($maxDateRaw);
            if ($lastModify->lt($maxDate)) {
                $diffInHours = $lastModify->diffInHours($maxDate);
                Log::info('ProcessTrackerUrl modified', [
                    'url' => $url->url,
                    'lastModify' => $lastModify,
                    'maxDate' => $maxDate,
                    'diffInHours' => $diffInHours,
                    'id' => $url->id
                ]);
                // SEMPRE atualiza
                $url->hash = md5($url->url);
                $url->last_modify = $maxDate;

                // Só notifica se for recente
                if ($diffInHours > 4 && $maxDate->gt($limitDate)) {
                    $user = User::getByIdStatic(-1);
                    $comment = TicketComment::create([
                        'html' => 'Acompanhamento da URL <a href="'.$url->url.'">'.$url->url.'</a><br/>',
                        'user_id' => $user->id,
                        'ticket_id' => $url->ticket_id
                    ]);
                    if ($notify) {
                        $ticket = Ticket::getById($url->ticket_id);
                        TicketParticipant::notifyParticipants(
                            $ticket,
                            $user,
                            TicketNotification::TYPE_TRACKER_URL,
                            $comment->id
                        );
                    }
                }
            } elseif ($lastModify->gt($maxDate)) {
                Log::info('ProcessTrackerUrl adjust database', [
                    'url' => $url->url,
                    'lastModify' => $lastModify,
                    'maxDate' => $maxDate,
                    'id' => $url->id
                ]);

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
        // 2. Pega datas dos <tr class="andamentoAberto">
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

        // 3. Pega datas dos <tr class="infraTrClara"> (colunas 3 e 4)
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

        return $maxDate;
    }
	
}

