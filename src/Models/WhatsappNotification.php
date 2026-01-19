<?php

namespace Oka6\SulRadio\Models;


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
    protected $casts = [
        'sent_at'=> 'datetime',
    ];
    const STATUS_NOTIFICATION_PENDING = 'pendente';
    const STATUS_NOTIFICATION_CREATE = 'criado';
    const STATUS_NOTIFICATION_IGNORED = 'ignorado';
    const STATUS_NOTIFICATION_SENDING = 'enviando';
    const STATUS_NOTIFICATION_SENT = 'disparado';
    const STATUS_NOTIFICATION_READ = 'lido';
    const STATUS_NOTIFICATION_CLICK = 'clicado';
    const STATUS_NOTIFICATION_ERROR = 'error';
    const STATUS_NOTIFICATION_INVALID_PHONE = 'telefone_invalido';
    const STATUS_NOTIFICATION_RECEIVED = 'recebido';

    const STATUS_NOTIFICATION_TRANSLATE = [
        self::STATUS_NOTIFICATION_PENDING       => 'Pendente',
        self::STATUS_NOTIFICATION_CREATE        => 'Criado',
        self::STATUS_NOTIFICATION_SENDING       => 'Enviando',
        self::STATUS_NOTIFICATION_READ          => 'Lido',
        self::STATUS_NOTIFICATION_IGNORED       => 'Ignorado',
        self::STATUS_NOTIFICATION_SENT          => 'Disparado',
        self::STATUS_NOTIFICATION_CLICK         => 'Clicado',
        self::STATUS_NOTIFICATION_ERROR         => 'Erro',
        self::STATUS_NOTIFICATION_INVALID_PHONE => 'Telefone invalido',
    ];
	protected $connection = 'sulradio';
    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function ticket():BelongsTo {
        return $this->belongsTo(Ticket::class);
    }

    public function comment():BelongsTo {
        return $this->belongsTo(TicketComment::class);
    }
    public static function getByTrID($trId, $type){
        return self::where('type', $type)->where('transaction_id', $trId)->first();
    }
    public static function getByCode($trId){
        return self::where('code', $trId)->first();
    }

}
