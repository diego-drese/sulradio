<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\Admin\Models\User;
use Oka6\SulRadio\Helpers\Helper;
use Oka6\SulRadio\Mail\TicketComment;
use Oka6\SulRadio\Mail\TicketCreate;
use Oka6\SulRadio\Mail\TicketDeadline;
use Oka6\SulRadio\Mail\TicketProtocolDeadline;
use Oka6\SulRadio\Mail\TicketRenewalAlert;
use Oka6\SulRadio\Mail\TicketTransfer;
use Oka6\SulRadio\Mail\TicketUpdate;
use Oka6\SulRadio\Mail\TicketCommentFromClient;
use Oka6\SulRadio\Models\SystemLog;
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
        $userSendNotification = [];
        $tries=[];
        foreach ($notifications as $notification){
            $try=count($tries);
            try {
                $keyMap = $notification->ticket_id.'-'.$notification->type.'-'.$notification->agent_current_id;
                if(!isset($userSendNotification[$keyMap])){
                    $notification->status = TicketNotification::STATUS_PROCESSED;
                    if ($notification->type == TicketNotification::TYPE_UPDATE) {
                        $notification->status = TicketNotification::STATUS_IGNORED;
                    }else if ($notification->type == TicketNotification::TYPE_COMMENT) {
                        $this->sendEmailTypeComment($notification, TicketNotification::TYPE_COMMENT);
                    }else if ($notification->type == TicketNotification::TYPE_TRACKER_URL) {
                        $this->sendEmailTypeComment($notification, TicketNotification::TYPE_TRACKER_URL);
                    } else if ($notification->type == TicketNotification::TYPE_DEADLINE) {
                        $this->sendEmailTypeDeadline($notification);
                    } else if ($notification->type == TicketNotification::TYPE_PROTOCOL_DEADLINE) {
                        $this->sendEmailTypeProtocolDeadline($notification);
                    }else if ($notification->type == TicketNotification::TYPE_RENEWAL_ALERT) {
                        $this->sendEmailTypeRenewalAlert($notification);
                    } else if ($notification->type == TicketNotification::TYPE_NEW) {
                        $this->sendEmailTypeNew($notification);
                    } else if ($notification->type == TicketNotification::TYPE_COMMENT_CLIENT) {
                        $this->sendEmailTypeCommentClient($notification);
                    } else if ($notification->type == TicketNotification::TYPE_TRANSFER_AGENT) {
                        $this->sendEmailTypeTransfer($notification);
                    }
                    Log::info('ProcessTicketNotification, email sent', ['notification'=>$notification->id, 'type'=>$notification->type]);
                }else{
                    $notification->status = TicketNotification::STATUS_IGNORED;
                    Log::info('ProcessTicketNotification, ignoring send email', ['keyMap'=>$keyMap, 'notification'=>$notification]);
                }
                $notification->save();

                if ($notification->type == TicketNotification::TYPE_TRACKER_URL){
                    /** Check send all notifications */
                    if(!TicketNotification::checkAllNotifications($notification->comment_id)){
                        \Oka6\SulRadio\Models\TicketComment::where('id', $notification->comment_id)->delete();
                    }
                }else{
                    $userSendNotification[$keyMap]=true;
                }
                /** Wait 1sec to send another email */
                sleep(1);
            } catch (\Exception $e) {
                $try++;
                Log::error('ProcessTicketNotification, retry send email', ['notification_id'=>$notification->id, 'try' => $try, 'e' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile(), 'tries'=>$tries]);
                sleep(15);
            }
        }

        //** Ajusta todos os processos que deram erro */
        $updatedProcessing = TicketNotification::where('status', TicketNotification::STATUS_PROCESSING)->count();
        if($updatedProcessing){
            TicketNotification::where('status', TicketNotification::STATUS_PROCESSING)->update([
                'status'=>TicketNotification::STATUS_WAITING
            ]);
            Log::error('ProcessTicketNotification, update error notification', ['total'=>$updatedProcessing]);
        }
        Log::info('ProcessTicketNotification, end process');
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
        Mail::to($currentAgent->email)->send(new TicketCreate($ticket));
        $content = "Novo ticket, enviado para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
    }

    public function sendEmailTypeDeadline($notification){
        $currentAgent   = User::getByIdStatic($notification->agent_current_id);
        $owner          = User::getByIdStatic($notification->user_logged);
        $ticket         = $this->getTicket($notification->ticket_id);
        $ticket->owner  = $owner;
        $ticket->agent  = $currentAgent;
        if($ticket->emissora){
            $ticket->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
        }
        Mail::to($currentAgent->email)->send(new TicketDeadline($ticket));
        $content = "Aviso de data de vencimento, enviado para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
    }

    public function sendEmailTypeProtocolDeadline($notification){
        $currentAgent   = User::getByIdStatic($notification->agent_current_id);
        $owner          = User::getByIdStatic($notification->user_logged);
        $ticket         = $this->getTicket($notification->ticket_id);
        $ticket->owner  = $owner;
        $ticket->agent  = $currentAgent;
        if($ticket->emissora){
            $ticket->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
        }
        Mail::to($currentAgent->email)->send(new TicketProtocolDeadline($ticket));
        $content = "Aviso de data de vencimento de protocolo, enviado para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
    }

    public function sendEmailTypeRenewalAlert($notification){
        $currentAgent   = User::getByIdStatic($notification->agent_current_id);
        $owner          = User::getByIdStatic($notification->user_logged);
        $ticket         = $this->getTicket($notification->ticket_id);
        $ticket->owner  = $owner;
        $ticket->agent  = $currentAgent;
        if($ticket->emissora){
            $ticket->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
        }
        Mail::to($currentAgent->email)->send(new TicketRenewalAlert($ticket));
    }

    public function sendEmailTypeComment($notification, $type){
        $currentAgent       = User::getByIdStatic($notification->agent_current_id);
        $userLogged         = User::getByIdStatic($notification->user_logged);
        $comment            = \Oka6\SulRadio\Models\TicketComment::getById($notification->comment_id);
        $ticket             = $this->getTicket($notification->ticket_id);
        if($comment){
            $comment->agent         = $currentAgent;
            $comment->userLogged    = $userLogged;
            $comment->emissora      = null;
            $comment->subject       = $ticket->subject;
            $comment->type          = $type;
            if($ticket->emissora){
                $comment->emissora = $ticket->desc_servico.'-'.$ticket->emissora.'('.$ticket->desc_municipio.' '.$ticket->desc_uf.')';
            }
            Mail::to($currentAgent->email)->send(new TicketComment($comment));
            $content = "Commentário id[{$comment->id}] enviado  para o email[{$currentAgent->email}]";
            SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
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
        Mail::to($currentAgent->email)->send(new TicketCommentFromClient($comment));
        $content = "Commentário do cliente id[{$comment->id}] enviado  para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
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
        Mail::to($currentAgent->email)->send(new TicketUpdate($ticket));
        $content = "Edição de ticket enviado  para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);
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
        $content = "Tranferencia de ticket enviado  para o email[{$currentAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $currentAgent->id);

        $ticket->sentTo   = 'OldAgent';
        Mail::to($oldAgent->email)->send(new TicketTransfer($ticket));
        $content = "Tranferencia de ticket enviado  para o email[{$oldAgent->email}]";
        SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $oldAgent->id);

        if($notification->user_logged!=$notification->owner_id){
            $ticket->sentTo   = 'Owner';
            Mail::to($owner->email)->send(new TicketTransfer($ticket));
            $content = "Tranferencia de ticket enviado  para o email[{$owner->email}]";
            SystemLog::insertLog(SystemLog::ZONE_SEAD, SystemLog::TYPE_SEND_EMAIL, $content, $notification->ticket_id, $owner->id);
        }
    }
}

