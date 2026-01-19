<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Oka6\Admin\Http\Library\ResourceAdmin;
use Oka6\SulRadio\Models\WhatsappNotification;
use Oka6\SulRadio\Service\WhatsAppService;
use Yajra\DataTables\DataTables;

class WhatsappController extends SulradioController {
	use ValidatesRequests;
	
	public function index(Request $request) {
		if ($request->ajax()) {
			$date = $request->get('date');
			$query = WhatsappNotification::with(['user', 'ticket', 'comment', 'ticket.emissora']);

			if ($date) {
				list($dateStart, $dateEnd) = array_map('trim', explode('-', $date));
				$dateStartObj = new \DateTime();
				$dateStart = $dateStartObj->createFromFormat('d/m/Y H:i', $dateStart . ' 00:00');
				$dateEndObj = new \DateTime();
				$dateEnd = $dateEndObj->createFromFormat('d/m/Y H:i', $dateEnd . ' 23:59');
				$query->where('created_at', '>=', $dateStart)->where('exame_datahora', '<=', $dateEnd);
			}
			return DataTables::of($query)
				->addColumn('ticket_url', function ($row) {
					return route('ticket.ticket', [$row->ticket_id]);
				})->addColumn('status', function ($row) {
					return WhatsappNotification::STATUS_NOTIFICATION_TRANSLATE[$row->status];
				})->addColumn('sent_at', function ($row) {
					return $row->sent_at ? $row->sent_at->timeZone('America/Sao_paulo')->format('d/m/Y H:i') : '---';
				})->setRowClass(function () {
					return 'center';
				})
				->toJson(true);
		}
		return $this->renderView('SulRadio::backend.whatsapp.index', []);
		
	}
	
	
	protected function makeParameters($extraParameter = null) {
		$parameters = [
			'hasEdit' => ResourceAdmin::hasResourceByRouteName('emissora.atos.oficiais.edit', [1, 1]),
		];
		$this->parameters = $parameters;
	}

    public function qr(WhatsAppService $whatsAppService): JsonResponse {
        $response = $whatsAppService->getQrCode();
        Log::info("WhatsappController:qr QR Code response:", [$response]);
        if (!$response) {
            return response()->json([
                'authenticated' => false,
                'message' => 'QR não disponível, verifique o container',
            ]);
        }elseif(isset($response['authenticated']) && $response['authenticated'] === true){
            return response()->json([
                'authenticated' => true,
                'message' => 'Sessão já autenticada',
            ]);
        }

        return response()->json([
            'authenticated' => false,
            'qr' => $response['qr'],
        ]);
    }
    public function logout(WhatsAppService $whatsAppService): JsonResponse {
        $logout = $whatsAppService->logout();
        Log::info('WhatsappController::logout: ', [$logout]);
        if($logout){
            return response()->json(['message' => 'Sessão deslogada com sucesso']);
        }
        return response()->json(['message' => 'Sessão não esta ativa'], 500);
    }
    public function webhook(Request $request){
        Log::info('WhatsAppService::webhook', $request->all());
        try {
            $notification   = WhatsappNotification::getByCode($request->input('id'));
            $status         = $request->get('status');
            $statusMap      = [
                'pending'   => WhatsappNotification::STATUS_NOTIFICATION_PENDING,  // 'pendente'
                'server'    => WhatsappNotification::STATUS_NOTIFICATION_PENDING,  // 'pendente'
                'retry'     => WhatsappNotification::STATUS_NOTIFICATION_PENDING,  // 'pendente'
                'delivered' => WhatsappNotification::STATUS_NOTIFICATION_PENDING,  // 'recebido'
                'read'      => WhatsappNotification::STATUS_NOTIFICATION_PENDING,     // 'lido'
            ];

            $phpStatus = $statusMap[$status] ?? WhatsappNotification::STATUS_NOTIFICATION_PENDING;
            if($notification && $notification->status != WhatsappNotification::STATUS_NOTIFICATION_CLICK && ($phpStatus==WhatsappNotification::STATUS_NOTIFICATION_RECEIVED || $phpStatus==WhatsappNotification::STATUS_NOTIFICATION_READ)){
                $notification->status = $phpStatus;
                $notification->save();
                $notification->invoice->update(['status_notification' => $phpStatus]);
            }
        }catch (\Exception $e){
            Log::error('WhatsAppService::webhook, update invoice error', ['error' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile(), 'request' => $request->all()]);
        }
        return response()->json([]);
    }
}