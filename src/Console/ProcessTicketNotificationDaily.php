<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Mail\TicketComment;
use Oka6\SulRadio\Mail\TicketCreate;
use Oka6\SulRadio\Mail\TicketDaily;
use Oka6\SulRadio\Mail\TicketTransfer;
use Oka6\SulRadio\Mail\TicketUpdate;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketNotification;


class ProcessTicketNotificationDaily extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessTicketNotificationDaily';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process notifications from ticket daily';
	
	
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}
	
	public function handle() {
		$now = new \DateTime();
		$day = (int)$now->format('N');
		$holiday = $now->format('d/m');
		
		if(App::environment('production') && $day>5){
			Log::info('ProcessTicketNotificationDaily, end process, weekend['.$day.']');
			return true;
		}
		
		if(App::environment('production') && $holiday=='01/01'){
			Log::info('ProcessTicketNotificationDaily, end process, Ano novo');
			return true;
		}
		if(App::environment('production') && $holiday=='02/02'){
			Log::info('ProcessTicketNotificationDaily, end process, Nossa Senhora dos Navegantes');
			return true;
		}
		if(App::environment('production') && $holiday=='21/04'){
			Log::info('ProcessTicketNotificationDaily, end process, Dia de Tiradentes');
			return true;
		}
		if(App::environment('production') && $holiday=='01/05'){
			Log::info('ProcessTicketNotificationDaily, end process, Dia do trabalho');
			return true;
		}
		if(App::environment('production') && $holiday=='07/09'){
			Log::info('ProcessTicketNotificationDaily, end process, Independência do Brasil');
			return true;
		}
		if(App::environment('production') && $holiday=='20/09'){
			Log::info('ProcessTicketNotificationDaily, end process, Proc. República Rio Grandense');
			return true;
		}
		if(App::environment('production') && $holiday=='12/10'){
			Log::info('ProcessTicketNotificationDaily, end process, Proc. Nossa Senhora Aparecida');
			return true;
		}
		
		if(App::environment('production') && $holiday=='02/11'){
			Log::info('ProcessTicketNotificationDaily, end process, Finados');
			return true;
		}
		
		if(App::environment('production') && $holiday=='15/11'){
			Log::info('ProcessTicketNotificationDaily, end process, Proclamação da República');
			return true;
		}
		
		if(App::environment('production') && $holiday=='25/12'){
			Log::info('ProcessTicketNotificationDaily, end process, Natal');
			return true;
		}
		
		Log::info('ProcessTicketNotificationDaily, start process');
		$users = User::where('active', 1)->get();
		foreach ($users as $user){
			/** Testa somente alguns usuários */
			if(App::environment('local') || ($user->id==1 || $user->id==2|| $user->id==6 || $user->id==10)){
				/** Tickets from user Not Complete*/
				$daily = [
					'user'=>$user,
					'owner'=>Ticket::getAllByOwnerId($user->id, false),
					'agent'=>Ticket::getAllByAgentId($user->id, false),
					'each'=>Ticket::getAllByAgentId($user->id, false, true),
				];
				if(count($daily['owner']) || count($daily['agent']) || count($daily['each'])){
					Mail::to($user->email)->send(new TicketDaily($daily));
				}
			}
		}
	}
	public function getTicket($field, $id){
		return Ticket::withSelectDataTable()
			->withStatus()
			->WithPriority()
			->withCategory()
			->withEmissora()
			->withServico()
			->withLocalidade()
			->withUf()
			->where($field, $id)
			->get();
	}
	public function sendEmailTypeNew($notification){
		$currentAgent   = User::getByIdStatic($notification->agent_current_id);
		$owner          = User::getByIdStatic($notification->owner_id);
		$ticket         = $this->getTicket($notification->ticket_id);
		$ticket->owner  = $owner;
		$ticket->agent  = $currentAgent;
		Mail::to($currentAgent->email)->send(new TicketCreate($ticket));
	}
	public function sendEmailTypeComment($notification){
		$userLogged =null;
		if($notification->user_logged!=$notification->agent_current_id){
			$currentAgent       = User::getByIdStatic($notification->agent_current_id);
			$userLogged         = User::getByIdStatic($notification->user_logged);
			$comment             = \Oka6\SulRadio\Models\TicketComment::getById($notification->comment_id);
			$comment->agent      = $currentAgent;
			$comment->userLogged = $userLogged;
			Mail::to($currentAgent->email)->send(new TicketComment($comment));
		}
		
		if($notification->user_logged!=$notification->owner_id){
			$owner               = User::getByIdStatic($notification->owner_id);
			$userLogged          = $userLogged ? $userLogged : User::getByIdStatic($notification->user_logged);
			$comment             = \Oka6\SulRadio\Models\TicketComment::getById($notification->comment_id);
			$comment->agent      = $owner;
			$comment->userLogged = $userLogged;
			Mail::to($owner->email)->send(new TicketComment($comment));
		}
		
		
	}
	
	public function sendEmailTypeUpdate($notification){
		$userLogged         = User::getByIdStatic($notification->user_logged);
		$currentAgent       = User::getByIdStatic($notification->agent_current_id);
		$owner              = User::getByIdStatic($notification->owner_id);
		
		$ticket             = $this->getTicket($notification->ticket_id);
		$ticket->owner      = $owner;
		$ticket->agent      = $currentAgent;
		$ticket->userLogged = $userLogged;
		if($notification->user_logged!=$notification->agent_current_id){
			Mail::to($currentAgent->email)->send(new TicketUpdate($ticket));
		}
		
		if($notification->user_logged!=$notification->owner_id){
			$ticket->emailToOwner = true;
			Mail::to($owner->email)->send(new TicketUpdate($ticket));
		}
		
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

