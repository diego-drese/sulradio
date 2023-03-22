<?php

namespace Oka6\SulRadio\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Oka6\Admin\Library\MongoUtils;
use Oka6\SulRadio\Models\EstacaoRd;
use Yajra\DataTables\DataTables;
use Rap2hpoutre\FastExcel\FastExcel;

class AnatelController extends SulradioController {
	use ValidatesRequests;
	
	public function emissoras(Request $request) {
        if ($request->has('export') && $request->get('export') == 'true') {
            try {
                $request->request->add([
                    'start'=>0,
                    'length'=>5000,
                    'search'=>['value'=>'', 'regex'=>false]

                ]);
                $dataTable      = $this->dataTable($request);
                $registersCount = 0;
                $registersTotal = $dataTable->original['recordsTotal'];
                $list           = [];

                while ($registersCount<$registersTotal){
                    $registersCount+= count($dataTable->original['data']);
                    foreach ($dataTable->original['data'] as $data){
                        $list[]=[
                            'fistel'        => $data['fistel'],
                            'uf'            => $data['uf'],
                            'municipio'     => $data['municipio'],
                            'servico'       => isset($data['servico']) ? $data['servico'] : '',
                            'canal'         => isset($data['canal']) ? $data['canal'] : '',
                            'frequencia'    => isset($data['frequencia']) ? $data['frequencia'] : null,
                            'finalidade'    => $data['entidade']['finalidade'],
                            'classe'        => isset($data['classe']) ? $data['classe'] : null,
                            'status'        => isset($data['state']) ? $data['state'] : null,
                            'Entidade'      => $data['entidade']['entidade_nome_entidade'],
                            'data'          => $data['updated_at'],
                            'vencimento'    => $data['entidade']['habilitacao_datavalfreq'],
                        ];
                    }
                    $request->request->add([
                        'start'=>$registersCount,
                    ]);
                    Log::info('SubscriptionController make csv', ['lines_append'=>$registersCount]);
                    $dataTable      = $this->dataTable($request);
                }
                $fastExcel = new FastExcel();
                $fastExcel->configureCsv(';');
                return ($fastExcel->data($list))->download("file.csv");
            }catch (\Exception $e){
                return response('Erro ao processar seu arquivo. Filtre os resultados para gerar um arquivo menor', 500);
            }

        }

		if ($request->ajax()) {
            return $this->dataTable($request);
		}
		return $this->renderView('SulRadio::backend.estacao-rd.index', []);
		
	}
    public function dataTable(Request $request){
        $date       = $request->get('date');
        $fistel     = $request->get('fistel');
        $uf         = $request->get('uf');
        $municipio  = $request->get('municipio');
        $servico    = $request->get('servico');
        $canal      = $request->get('canal');
        $frequencia = $request->get('frequencia');
        $finalidade = $request->get('finalidade');//entidade.finalidade
        $classe     = $request->get('classe');
        $status     = $request->get('status');
        $entidade   = $request->get('entidade');//entidade.entidade_nome_entidade
        $vencimento = $request->get('vencimento');
        $query  = EstacaoRd::query();
        if ($date) {
            list($dateStart, $dateEnd) = array_map('trim', explode('-', $date));
            $dateStartObj = new \DateTime();
            $dateStart = $dateStartObj->createFromFormat('d/m/Y H:i', $dateStart . ' 00:00');
            $dateEndObj = new \DateTime();
            $dateEnd = $dateEndObj->createFromFormat('d/m/Y H:i', $dateEnd . ' 23:59');
            $query->where('exame_datahora', '>=', MongoUtils::convertDatePhpToMongo($dateStart))->where('exame_datahora', '<=', MongoUtils::convertDatePhpToMongo($dateEnd));
        }
        if($fistel){
            $query->where('fistel', 'LIKE', "%{$fistel}%");
        }
        if($uf){
            $query->where('uf', $uf);
        }
        if($municipio){
            $query->where('municipio', 'LIKE', "%{$municipio}%");
        }
        if($servico){
            $query->where('servico', $servico);
        }
        if($canal){
            $query->where('canal', 'LIKE', "%{$canal}%");
        }
        if($frequencia){
            $query->where('frequencia', 'LIKE', "%{$frequencia}%");
        }
        if($finalidade){
            $query->where('entidade.finalidade', $finalidade);
        }
        if($classe){
            $query->where('classe', $classe);
        }
        if($status){
            $query->where('state', 'LIKE', "%{$status}%");
        }
        if($entidade){
            $query->where('entidade.entidade_nome_entidade', 'LIKE', "%{$entidade}%");
        }
        if($vencimento){
            $query->where('entidade.habilitacao_datavalfreq', 'LIKE', "%{$vencimento}%");
        }



        return DataTables::of($query)
            ->addColumn('edit_url', function ($row) {
                //	return route('emissora.edit', [$row->emissoraID]);
            })->toJson(true);
    }
	public function emissoraModal(Request $request, $_id) {
		$emissora = EstacaoRd::where('_id', new \MongoDB\BSON\ObjectId($_id))->first();
		return $this->renderView('SulRadio::backend.estacao-rd.modal', ['emissora'=>$emissora]);
		
	}
	protected function makeParameters($extraParameter = null) {
		$parameters = [
		];
		$this->parameters = $parameters;
	}
}