<?php

namespace Oka6\SulRadio\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Oka6\Admin\Models\User;

class WhatsappNotification extends Model {
	protected $fillable = [
        'ticket_id',
        'ticket_comment_id',
        'type',
        'destination',
        'transaction_id',
        'code',
        'status',
        'description',
        'body',
	];
	protected $connection = 'sulradio';
    public function users():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function ticket():BelongsTo {
        return $this->belongsTo(Ticket::class);
    }
    public function comment():BelongsTo {
        return $this->belongsTo(TicketComment::class);
    }

}
