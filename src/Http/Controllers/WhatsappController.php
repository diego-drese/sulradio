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
				->addColumn('edit_url', function ($row) {
					return '#';
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
        }elseif(isset($response['authenticated'])){
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
}