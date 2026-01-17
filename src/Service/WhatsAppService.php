<?php
namespace Oka6\SulRadio\Service;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

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
}
