<?php

namespace Oka6\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;
use Oka6\SulRadio\Models\DocumentType;


class DocumentTypeTableSeed extends Seeder {
	public function run() {
		if(!DocumentType::count()){
			$list = [
				"Acordo",
				"Processo",
				"Licenciamento",
				"Medição",
				"Multas",
				"Documento Fiscal",
				"Contrato social",
				"Certidão",
				"Balanço patrimonial",
				"Demonstrativo resultado exercício",
				"Regularidade FISTEL",
				"Regularidade FGTS",
				"Laudo de vistoria e ART",
				"CND Justiça do Trabalho",
				"CND Estadual",
				"CND Municipal",
				"CND Receita Federal"
			];
			
			foreach ($list as $value){
				DocumentType::create([
					'name'=> $value,
					'description'=> '',
					'is_active'=> '1',
				]);
			}
		}
	}
}
