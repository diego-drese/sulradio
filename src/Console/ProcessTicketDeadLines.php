<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Mail\TicketComment;
use Oka6\SulRadio\Mail\TicketCreate;
use Oka6\SulRadio\Mail\TicketTransfer;
use Oka6\SulRadio\Mail\TicketUpdate;
use Oka6\SulRadio\Mail\TicketCommentFromClient;
use Oka6\SulRadio\Models\Ticket;
use Oka6\SulRadio\Models\TicketNotification;
use Oka6\SulRadio\Models\TicketParticipant;
use Oka6\SulRadio\Models\TicketStatus;


class ProcessTicketDeadLines extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'Sulradio:ProcessTicketDeadLines';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description  = 'Process deadlines from ticket';


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
		Log::info('ProcessTicketDeadLines, start process');
        $proccessDays           = [ 395, 365, 180, 90, 60, 45, 30, 20, 15, 10, 5, 4, 3, 2, 1];
        $statusDeadline         = TicketStatus::getStatusDeadLine(TicketStatus::STATUS_DEADLINE);
        $statusProtocolDeadline = TicketStatus::getStatusDeadLine(TicketStatus::STATUS_PROTOCOL_DEADLINE);
        if(!$statusDeadline){
            Log::info('ProcessTicketDeadLines, dont exists status with deadline');
        }
        if(!$statusProtocolDeadline){
            Log::info('ProcessTicketDeadLines, dont exists status with protocol deadline');
        }
        $userLogged   = User::getByIdStatic(-1);
        foreach ($proccessDays as $day){
            if($statusDeadline){
                $tickets = Ticket::getDeadLines($day, $statusDeadline->id);
                foreach ($tickets as $ticket){
                    TicketParticipant::notifyParticipants($ticket, $userLogged, TicketNotification::TYPE_DEADLINE, null , $day);
                }
            }

            if($statusProtocolDeadline){
                $tickets = Ticket::getProtocolDeadLines($day, $statusProtocolDeadline->id);
                foreach ($tickets as $ticket){
                    TicketParticipant::notifyParticipants($ticket, $userLogged, TicketNotification::TYPE_PROTOCOL_DEADLINE, null , $day);
                }
            }

        }
        /** Renew alert */
        $proccessDays           = [3650, 2555, 1825, 1460, 1095, 730, 365, 180, 90, 60, 45, 30, 20, 15, 10, 5, 4, 3, 2, 1];
        foreach ($proccessDays as $day){
            $tickets = Ticket::getRenewalAlert($day);
            foreach ($tickets as $ticket){
                TicketParticipant::notifyParticipants($ticket, $userLogged, TicketNotification::TYPE_RENEWAL_ALERT, null , $day);
            }
        }
	}
}

