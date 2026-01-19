<?php
namespace Oka6\SulRadio\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Oka6\SulRadio\Models\WhatsappNotification;

final class WhatsAppService {
    public function getQrCode(){
        $qrUrl = Config::get('sulradio.whatsapp_qr_url');
        if (!$qrUrl) {
            return null;
        }
        try {
            $response = Http::timeout(5)->get($qrUrl);
            if ($response->ok()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }
    public function logout(){
        $logoutUrl = Config::get('sulradio.whatsapp_logout_url');
        if (!$logoutUrl) {
            return null;
        }
        try {
            $response = Http::timeout(30)->get($logoutUrl);
            return $response->json();
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }
    protected function parserCellphone(string $rawPhone): ?string{
        // Remove tudo que não for número
        $digits = preg_replace('/\D/', '', $rawPhone);
        // Valida 10 ou 11 dígitos (DDD + número)
        if (!preg_match('/^\d{10,11}$/', $digits)) {
            return null;
        }

        // Adiciona DDI Brasil
        return '55' . $digits;
    }

    public function sendMessage(WhatsappNotification $whatsappNotification, $cellphone){
        try {
            $parserCellphone = $this->parserCellphone($cellphone);
            if(!$parserCellphone){
                $whatsappNotification->status       = WhatsappNotification::STATUS_NOTIFICATION_INVALID_PHONE;
                $whatsappNotification->description = 'Telefone inválido: esperado 10 ou 11 dígitos (ex: (99) 9999-9999 ou (99) 9 9999-9999). Telefone atual:'.$cellphone;
                $whatsappNotification->save();
                return;
            }
            $url        = Config::get('sulradio.whatsapp_send');
            $route      = route('ticket.config.whatsapp.webhook');
            $path       = parse_url($route, PHP_URL_PATH);
            $webhook    = Config::get('sulradio.url_internal').$path;
            $response = Http::post($url, [
                'number' => $parserCellphone,
                'message' => $whatsappNotification->body,
                'webhookUrl' => $webhook,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("WhatsApp enviado com sucesso", [
                    'message_id' => $data['id'] ?? null,
                    'to'         => $data['to'] ?? $cellphone,
                    'webhookUrl' => $webhook,
                ]);
                $whatsappNotification->code         = $data['id'];
                $whatsappNotification->destination  = $data['to'] ?? $cellphone;
                $whatsappNotification->status       = WhatsappNotification::STATUS_NOTIFICATION_SENT;
                $whatsappNotification->save();

            } else {
                $data = $response->json();
                Log::error("Falha ao enviar WhatsApp", [
                    'response' => $response->body(),
                    'destination' => $cellphone,
                    'contract_invoice_id' => $whatsappNotification->ticket_id,
                    'transaction_id' => $whatsappNotification->transaction_id,
                ]);
                $whatsappNotification->status       = WhatsappNotification::STATUS_NOTIFICATION_ERROR;
                $whatsappNotification->code         = $data['id'] ?? null;
                $whatsappNotification->destination  = $data['to'] ?? $cellphone;
                $whatsappNotification->sent_at      = Carbon::now();
                $whatsappNotification->save();

            }

        } catch (\Throwable $e) {
            Log::error("Erro no envio de WhatsApp", ['error' => $e->getMessage(), 'trId'=>$whatsappNotification->transaction_id, 'cellphone'=>$cellphone]);
        }
    }

}
