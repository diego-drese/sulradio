<?php

namespace Oka6\SulRadio\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TicketCommentClient extends Mailable {
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	protected $data;
	protected $attach;

	public function __construct($data, $attach=[]) {
		$this->data = $data;
		$this->attach = $attach;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		$message = $this->subject('Sead Sulradio')->markdown('SulRadio::backend.emails.ticket-comment-client',['data' => $this->data]);
		foreach ($this->attach as $attach) {
			//$fileUrl = Storage::disk('spaces')->temporaryUrl('tickets/'.$attach->file_name, now()->addMinutes(5));
			$message->attachFromStorageDisk('spaces', 'tickets/'.$attach->file_name);
        }
		return $message;
	}
}
