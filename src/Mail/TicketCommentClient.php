<?php

namespace Oka6\SulRadio\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCommentClient extends Mailable {
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	protected $data;
	protected $attach;
    protected $subjectEmail;
	public function __construct($data, $attach=[], $subjectEmail=null) {
		$this->data = $data;
		$this->attach = $attach;
        $this->subjectEmail= $subjectEmail;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
        if($this->subjectEmail){
            $message = $this->subject($this->subjectEmail)->markdown('SulRadio::backend.emails.ticket-comment-client',['data' => $this->data]);
        }else{
            $message = $this->subject('Sead Sulradio')->markdown('SulRadio::backend.emails.ticket-comment-client',['data' => $this->data]);
        }

		foreach ($this->attach as $attach) {
			//$fileUrl = Storage::disk('spaces')->temporaryUrl('tickets/'.$attach->file_name, now()->addMinutes(5));
			$message->attachFromStorageDisk('spaces', 'tickets/'.$attach->file_name);
        }
		return $message;
	}
}
