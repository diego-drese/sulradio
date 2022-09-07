<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use IWasHereFirst2\LaravelMultiMail\Facades\MultiMail;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Mail\TicketComment;
use Oka6\SulRadio\Mail\TicketCreate;
use Oka6\SulRadio\Mail\TicketTransfer;
use Oka6\SulRadio\Mail\TicketUpdate;
use Oka6\SulRadio\Mail\TicketCommentFromClient;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketNotification;


class ProcessTicketNotification extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessTicketNotification';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process notifications from ticket';


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
		Log::info('ProcessTicketNotification, start process');
		$notifications = TicketNotification::getToNotify();
		$emailCount=Helper::getEmailCount();
		$userSendNotification=[];
		$tries=[];
		foreach ($notifications as $notification){
			$try=count($tries);
			while ($try < $emailCount) {
				$this->emailFrom = Helper::sendEmailRandom($tries);
				try {
                    $keyMap = $notification->ticket_id.'-'.$notification->type.'-'.$notification->agent_current_id;
                    if(!isset($userSendNotification[$keyMap])){
                        $notification->status = TicketNotification::STATUS_PROCESSED;
                        if ($notification->type == TicketNotification::TYPE_UPDATE) {
                            $this->sendEmailTypeUpdate($notification);
                        }else if ($notification->type == TicketNotification::TYPE_COMMENT) {
                            $this->sendEmailTypeComment($notification);
                        } else if ($notification->type == TicketNotification::TYPE_NEW) {
                            $this->sendEmailTypeNew($notification);
                        } else if ($notification->type == TicketNotification::TYPE_COMMENT_CLIENT) {
                            $this->sendEmailTypeCommentClient($notification);
                        } else if ($notification->type == TicketNotification::TYPE_TRANSFER_AGENT) {
                            $this->sendEmailTypeTransfer($notification);
                        }
                        $notification->save();
                        $try = $emailCount;
                        $userSendNotification[$keyMap]=true;
                    }else{
                        Log::info('ProcessTicketNotification, ignoring send email', ['keyMap'=>$keyMap, 'notification'=>$notification]);
                    }


				} catch (\Exception $e) {
					$try++;
					$tries[] = $this->emailFrom['email'];
					Log::error('ProcessTicketNotification, retry send email', ['try' => $try, 'e' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile(), 'tries'=>$tries]);
				}
			}
		}
	}
	public function getTicket($id){
		return Ticket::withSelectDataTable()
			->withStatus()
			->WithPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where('ticket.id', $id)
			->first();
	}
	public function sendEmailTypeNew($notification){
		$currentAgent   = User::getByIdStatic($notification->agent_current_id);
		$owner          = User::getByIdStatic($notification->user_logged);
		$ticket         = $this->getTicket($notification->ticket_id);
		$ticket->owner  = $owner;
		$ticket->agent  = $currentAgent;
		if($ticket->emissora){
			$ticket->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
		}
		MultiMail::to($currentAgent->email)->from($this->emailFrom['email'])->send(new TicketCreate($ticket));
	}

	public function sendEmailTypeComment($notification){
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$comment            = \Oka6\SulRadio\Models\TicketComment::getById($notification->comment_id);
		$ticket             = $this->getTicket($notification->ticket_id);
		if($comment){
			$comment->agent         = $currentAgent;
			$comment->userLogged    = $userLogged;
			$comment->emissora      = null;
			$comment->subject       = $ticket->subject;
			if($ticket->emissora){
				$comment->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
			}
			MultiMail::to($currentAgent->email)->from($this->emailFrom['email'])->send(new TicketComment($comment));
		}else{
			Log::error('ProcessTicketNotification sendEmailTypeComment, comment not found', ['notification'=>$notification]);
		}
	}
	public function sendEmailTypeCommentClient($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$comment            = \Oka6\SulRadio\Models\TicketNotificationClientUser::getById($notification->comment_id);
		$clientUser         = \Oka6\SulRadio\Models\TicketNotificationClient::getById($comment->ticket_notification_client_id);
		$ticket             = $this->getTicket($clientUser->ticket_id);
		$comment->agent     = $currentAgent;
		$comment->userLogged= $userLogged;
		$comment->ticket_id = $clientUser->ticket_id;
		$comment->emissora  = null;
		$comment->subject   = $ticket->subject;
		if($ticket->emissora){
			$comment->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
		}
		MultiMail::to($currentAgent->email)->from($this->emailFrom['email'])->send(new TicketCommentFromClient($comment));
	}

	public function sendEmailTypeUpdate($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$owner              = User::getByIdStatic($notification->owner_id);
		$ticket             = $this->getTicket($notification->ticket_id);
		$ticket->owner      = $owner;
		$ticket->agent      = $currentAgent;
		$ticket->userLogged = $userLogged;
		if($ticket->emissora){
			$ticket->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
		}
		MultiMail::to($currentAgent->email)->from($this->emailFrom['email'])->send(new TicketUpdate($ticket));
	}

	public function sendEmailTypeTransfer($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$oldAgent           = User::getByIdStatic($notification->agent_old_id);
		$owner              = User::getByIdStatic($notification->owner_id);

		$ticket             = $this->getTicket($notification->ticket_id);
		$ticket->owner      = $owner;
		$ticket->oldAgent   = $oldAgent;
		$ticket->agent      = $currentAgent;
		$ticket->userLogged = $userLogged;

		$ticket->sentTo   = 'CurrentAgent';
		MultiMail::to($currentAgent->email)->from($this->emailFrom['email'])->send(new TicketTransfer($ticket));
		$ticket->sentTo   = 'OldAgent';
		MultiMail::to($oldAgent->email)->from($this->emailFrom['email'])->send(new TicketTransfer($ticket));
		if($notification->user_logged!=$notification->owner_id){
			$ticket->sentTo   = 'Owner';
			MultiMail::to($owner->email)->from($this->emailFrom['email'])->send(new TicketTransfer($ticket));
		}

	}


}

