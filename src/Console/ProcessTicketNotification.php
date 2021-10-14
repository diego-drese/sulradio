<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\Admin\Models\User;
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
		foreach ($notifications as $notification){
			if($notification->type==TicketNotification::TYPE_NEW){
				$this->sendEmailTypeNew($notification);
			}else if($notification->type==TicketNotification::TYPE_UPDATE){
				$this->sendEmailTypeUpdate($notification);
			}else if($notification->type==TicketNotification::TYPE_COMMENT_CLIENT){
				$this->sendEmailTypeCommentClient($notification);
			}else if($notification->type==TicketNotification::TYPE_COMMENT){
				$this->sendEmailTypeComment($notification);
			}else if($notification->type==TicketNotification::TYPE_TRANSFER_AGENT){
				$this->sendEmailTypeTransfer($notification);
			}
			$notification->status = TicketNotification::STATUS_PROCESSED;
			$notification->save();
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
		Mail::to($currentAgent->email)->send(new TicketCreate($ticket));
	}
	
	public function sendEmailTypeComment($notification){
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$comment             = \Oka6\SulRadio\Models\TicketComment::getById($notification->comment_id);
		$comment->agent      = $currentAgent;
		$comment->userLogged = $userLogged;
		Mail::to($currentAgent->email)->send(new TicketComment($comment));
	}
	public function sendEmailTypeCommentClient($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$comment             = \Oka6\SulRadio\Models\TicketNotificationClientUser::getById($notification->comment_id);
		$comment->agent      = $currentAgent;
		$comment->userLogged = $userLogged;
		Mail::to($currentAgent->email)->send(new TicketCommentFromClient($comment));
	}
	
	public function sendEmailTypeUpdate($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$owner              = User::getByIdStatic($notification->owner_id);
		
		$ticket             = $this->getTicket($notification->ticket_id);
		$ticket->owner      = $owner;
		$ticket->agent      = $currentAgent;
		$ticket->userLogged = $userLogged;
		Mail::to($currentAgent->email)->send(new TicketUpdate($ticket));
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
		Mail::to($currentAgent->email)->send(new TicketTransfer($ticket));
		$ticket->sentTo   = 'OldAgent';
		Mail::to($oldAgent->email)->send(new TicketTransfer($ticket));
		if($notification->user_logged!=$notification->owner_id){
			$ticket->sentTo   = 'Owner';
			Mail::to($owner->email)->send(new TicketTransfer($ticket));
		}
		
	}
	
	
}

