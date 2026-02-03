<?php

namespace Oka6\SulRadio\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Oka6\SulRadio\Models\TicketNotification;

class TicketComment extends Mailable {
	use Queueable, SerializesModels;
	
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	protected $data;
	
	public function __construct($data) {
		$this->data = $data;
	}
	
	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
        if($this->data->type==TicketNotification::TYPE_COMMENT){
            return $this->subject('Comentário-'.$this->data->subject.' '.$this->data->emissora)
                ->markdown('SulRadio::backend.emails.ticket-comment', ['data' => $this->data, 'url'=>'']);
        }

        return $this->subject('Acompanhamento de processo-'.$this->data->emissora)
            ->markdown('SulRadio::backend.emails.ticket-comment', ['data' => $this->data, 'url'=>'']);
	}
}
