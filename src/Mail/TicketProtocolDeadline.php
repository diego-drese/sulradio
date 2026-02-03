<?php

namespace Oka6\SulRadio\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketProtocolDeadline extends Mailable {
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
        $now = Carbon::now()->setTime(0,0,0,);
        $startDeadline = Carbon::createFromFormat('d/m/Y', $this->data->end_forecast);
        $days =  $startDeadline->diffInDays($now);
		return $this->subject('Prazo de protocolo-'.$this->data->subject.' '.$this->data->emissora)
			->markdown('SulRadio::backend.emails.ticket-protocol-deadline', ['data' => $this->data, 'days'=>$days]);
	}
}
