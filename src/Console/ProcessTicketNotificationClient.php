<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\SulRadio\Mail\TicketCommentClient;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketNotificationClient;
use Oka6\SulRadio\Models\TicketNotificationClientUser;


class ProcessTicketNotificationClient extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessTicketNotificationClient';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process notifications client from ticket comment';
	
	
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public function handle() {
		Log::info('ProcessTicketNotificationClient, start process');
		$notifications = TicketNotificationClient::getToNotify();
		foreach ($notifications as $notification){
			$this->sendEmail($notification);

		}
	}

	public function getTicket($id){
		return Ticket::withSelectDataTable()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where('ticket.id', $id)
			->first();
	}
	public function sendEmail($notification){
		$usersToNotify  = TicketNotificationClientUser::getByTicketNotificationClientId($notification->id, false, false);
        $ticket = Ticket::query()
            ->withSelectDataTable()
            ->withStatus()
            ->withPriority()
            ->withCategory()
            ->withEmissora()
            ->withServico()
            ->withLocalidade()
            ->withUf()
            ->where('ticket.id', $notification->ticket_id)
            ->first();

        $subject= $ticket->subject.' - '.$ticket->desc_servico.'-'.$ticket->emissora;
		foreach ($usersToNotify as $userToNotify){
			$userToNotify->comment = $notification->comment;
			try {
                Mail::to($userToNotify->user_email)
                    ->bcc('sulradio@sulradio.com.br')
                    ->send(new TicketCommentClient($userToNotify, $notification->attach, $subject));
				$status = TicketNotificationClientUser::STATUS_SENT;
				$notification->total_send++;
			}catch (\Exception $e){
				Log::error('ProcessTicketNotificationClient sendEmail', ['e'=>$e->getMessage(), 'file'=>$e->getFile(), 'line'=>$e->getLine()]);
				$status = TicketNotificationClientUser::STATUS_ERROR;
			}
			$userToNotify->status = $status;
			if(isset($userToNotify->attach)){
				unset($userToNotify->attach);
			}
			if(isset($userToNotify->comment)){
				unset($userToNotify->comment);
			}
			$userToNotify->save();
		}

		unset($notification->attach);
		$notification->status = TicketNotificationClient::STATUS_SEND;
		$notification->send_date_at = date('Y-m-d H:i:s');
		$notification->save();
	}
}

