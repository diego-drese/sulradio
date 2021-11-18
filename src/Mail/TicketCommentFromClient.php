<?php

namespace Oka6\SulRadio\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCommentFromClient extends Mailable {
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
		$this->data->html = $this->data->answer;
		return $this->subject('Comentário no ticket')
			->markdown('SulRadio::backend.emails.ticket-comment', ['data' => $this->data, 'url'=>'']);
	}
}
