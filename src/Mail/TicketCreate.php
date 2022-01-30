<?php

namespace Oka6\SulRadio\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TicketCreate extends Mailable {
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
		return $this->subject('Novo-'.$this->data->subject.' '.$this->data->emissora)
			->markdown('SulRadio::backend.emails.ticket-create', ['data' => $this->data, 'url'=>'']);
	}
}
