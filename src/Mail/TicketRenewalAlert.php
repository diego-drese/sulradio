<?php

namespace Oka6\SulRadio\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketRenewalAlert extends Mailable {
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
        $renewalAlert = Carbon::createFromFormat('d/m/Y', $this->data->renewal_alert);
        $days =  $renewalAlert->diffInDays($now);
		return $this->subject('Prazo de vencimento-'.$this->data->subject.' '.$this->data->emissora)
			->markdown('SulRadio::backend.emails.ticket-renewal-alert', ['data' => $this->data, 'days'=>$days]);
	}
}
