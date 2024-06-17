<?php

namespace Oka6\SulRadio\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Oka6\SulRadio\Mail\AtoNotification as AtoNotificationMail;
use Oka6\SulRadio\Models\Ato;
use Oka6\SulRadio\Models\AtoNotification;
use Oka6\SulRadio\Models\UserSulRadio;


class ProcessAtoNotification extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sulradio:ProcessAtoNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process notifications from atos';


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
        Log::info('ProcessAtoNotification, start process');
        $notifications = AtoNotification::getToNotify();
        foreach ($notifications as $notification) {
            $this->emailFrom = 'nao-responda@sulradio.com';
            $ato = Ato::where('atoID', $notification->ato_id)
                ->joinEmissora()
                ->withCategoria()
                ->withTipo()
                ->first();
            $user = UserSulRadio::getByIdStatic($notification->user_id);
            try {
                $data = [
                    'user_name' => $user->name,
                    'razao_social' => $ato->razao_social,
                    'tipo_nome' => $ato->desc_tipo_ato,
                    'categoria_nome' => $ato->desc_categoria,
                    'secao' => $ato->secao,
                    'numero_ato' => $ato->numero_ato,
                    'data_ato' => $ato->data_ato,
                    'data_dou' => $ato->data_dou,
                    'url' => $ato->ato_url,
                ];

                Mail::to($user->email)
                    ->bcc(['sulradio@sulradio.com.br'])
                    ->send(new AtoNotificationMail($data));
                $notification->status = AtoNotification::STATUS_SEND;
                $notification->date_sent = date('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error('ProcessTicketNotification, exception', ['e' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
                $notification->status = AtoNotification::STATUS_ERROR;
                $notification->error_desc = $e->getMessage() . ' - ' . 'File:' . $e->getFile() . ' - Line:' . $e->getLine();
            }
            $notification->save();
        }
        Log::info('ProcessAtoNotification, end process');
    }
}

