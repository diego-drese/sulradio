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
	
	public function handle() {
		Log::info('ProcessTrackerUrl, start process');
		try {
			$urls = TicketUrlTracker::getNew();
			foreach ($urls as $url){
				$url->last_modify = date('Y-m-d H:i:s');
				$url->last_tracker = date('Y-m-d H:i:s');
				$url->hash = md5($this->parseDomain($url->url));
				$url->save();
			}
			$urls = TicketUrlTracker::getToCheck();
			foreach ($urls as $url){
				$html = $this->parseDomain($url->url);
				$hash =  md5($html);
				$url->last_tracker = date('Y-m-d H:i:s');
				if($hash!=$url->hash){
					$url->hash = $hash;
					$url->last_modify = date('Y-m-d H:i:s');
					$ticket = Ticket::getById($url->ticket_id);
					$user   = User::getByIdStatic(-1);
					$commentText = 'Acompanhamento da URL <a href="'.$url->url.'">'.$url->url.'</a><br/>'.$html;
					$comment = TicketComment::create([
						'content'=>$commentText,
						'html'=>$commentText,
						'user_id'=>$user->id,
						'ticket_id'=>$url->ticket_id
					]);
					/** Notifica todos os participantes */
					TicketParticipant::notifyParticipants($ticket, $user,TicketNotification::TYPE_COMMENT, $comment->id);
				}
				$url->save();
			}
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
		$parse = parse_url($url);
		$domain = $parse['host'];
		$dom = new \DOMDocument('1.0', 'UTF-8');
		$dom->recover = true;
		$dom->strictErrorChecking = false;
		@$dom->loadHTMLFile($url);
		switch ($domain){
			case 'sei.anatel.gov.br':
			case 'sei.mctic.gov.br':
				return $dom->saveXML($dom->getElementById('tblHistorico'));
			break;
			default;
				return $dom->saveXML($dom->getElementsByTagName('body'));
			break;
		}

	}
	
	
}

