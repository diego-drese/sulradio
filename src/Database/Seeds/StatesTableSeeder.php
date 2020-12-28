<?php
namespace Oka6\SulRadio\Database\Seeds;

use Illuminate\Database\Seeder;
use Oka6\SulRadio\Models\Countries;
use Oka6\SulRadio\Models\States;

class StatesTableSeeder extends Seeder {
	
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run() {
		if(Countries::count()){
			return false;
		}
		
		Countries::create([
			'id' => (int) (int)1,
			'title' => 'Brasil'
		]);
		
		States::insert(array(
			0 =>
				array(
					 'id' => (int) '1',
					'title' => 'Acre',
					 'country_id' => (int) '1',
					'letter' => 'AC',
					'iso' => '12',
					'slug' => 'acre',
					'population' => (int) '816687',
				),
			1 =>
				array(
					 'id' => (int) '2',
					'title' => 'Alagoas',
					 'country_id' => (int) '1',
					'letter' => 'AL',
					'iso' => '27',
					'slug' => 'alagoas',
					'population' => (int) '3358963',
				),
			2 =>
				array(
					 'id' => (int) '3',
					'title' => 'Amazonas',
					 'country_id' => (int) '1',
					'letter' => 'AM',
					'iso' => '13',
					'slug' => 'amazonas',
					'population' => (int) '4001667',
				),
			3 =>
				array(
					 'id' => (int) '4',
					'title' => 'Amapá',
					 'country_id' => (int) '1',
					'letter' => 'AP',
					'iso' => '16',
					'slug' => 'amapa',
					'population' => (int) '782295',
				),
			4 =>
				array(
					 'id' => (int) '5',
					'title' => 'Bahia',
					 'country_id' => (int) '1',
					'letter' => 'BA',
					'iso' => '29',
					'slug' => 'bahia',
					'population' => (int) '15276566',
				),
			5 =>
				array(
					 'id' => (int) '6',
					'title' => 'Ceará',
					 'country_id' => (int) '1',
					'letter' => 'CE',
					'iso' => '23',
					'slug' => 'ceara',
					'population' => (int) '8963663',
				),
			6 =>
				array(
					 'id' => (int) '7',
					'title' => 'Distrito Federal',
					 'country_id' => (int) '1',
					'letter' => 'DF',
					'iso' => '53',
					'slug' => 'distrito-federal',
					'population' => (int) '2977216',
				),
			7 =>
				array(
					 'id' => (int) '8',
					'title' => 'Espírito Santo',
					 'country_id' => (int) '1',
					'letter' => 'ES',
					'iso' => '32',
					'slug' => 'espirito-santo',
					'population' => (int) '3973697',
				),
			8 =>
				array(
					 'id' => (int) '9',
					'title' => 'Goiás',
					 'country_id' => (int) '1',
					'letter' => 'GO',
					'iso' => '52',
					'slug' => 'goias',
					'population' => (int) '6695855',
				),
			9 =>
				array(
					 'id' => (int) '10',
					'title' => 'Maranhão',
					 'country_id' => (int) '1',
					'letter' => 'MA',
					'iso' => '21',
					'slug' => 'maranhao',
					'population' => (int) '6954036',
				),
			10 =>
				array(
					 'id' => (int) '11',
					'title' => 'Minas Gerais',
					 'country_id' => (int) '1',
					'letter' => 'MG',
					'iso' => '31',
					'slug' => 'minas-gerais',
					'population' => (int) '20997560',
				),
			11 =>
				array(
					 'id' => (int) '12',
					'title' => 'Mato Grosso do Sul',
					 'country_id' => (int) '1',
					'letter' => 'MS',
					'iso' => '50',
					'slug' => 'mato-grosso-do-sul',
					'population' => (int) '2682386',
				),
			12 =>
				array(
					 'id' => (int) '13',
					'title' => 'Mato Grosso',
					 'country_id' => (int) '1',
					'letter' => 'MT',
					'iso' => '51',
					'slug' => 'mato-grosso',
					'population' => (int) '3305531',
				),
			13 =>
				array(
					 'id' => (int) '14',
					'title' => 'Pará',
					 'country_id' => (int) '1',
					'letter' => 'PA',
					'iso' => '15',
					'slug' => 'para',
					'population' => (int) '8272724',
				),
			14 =>
				array(
					 'id' => (int) '15',
					'title' => 'Paraiba',
					 'country_id' => (int) '1',
					'letter' => 'PB',
					'iso' => '25',
					'slug' => 'paraiba',
					'population' => (int) '3999415',
				),
			15 =>
				array(
					 'id' => (int) '16',
					'title' => 'Pernambuco',
					 'country_id' => (int) '1',
					'letter' => 'PE',
					'iso' => '26',
					'slug' => 'pernambuco',
					'population' => (int) '9410336',
				),
			16 =>
				array(
					 'id' => (int) '17',
					'title' => 'Piauí',
					 'country_id' => (int) '1',
					'letter' => 'PI',
					'iso' => '22',
					'slug' => 'piaui',
					'population' => (int) '3212180',
				),
			17 =>
				array(
					 'id' => (int) '18',
					'title' => 'Paraná',
					 'country_id' => (int) '1',
					'letter' => 'PR',
					'iso' => '41',
					'slug' => 'parana',
					'population' => (int) '11242720',
				),
			18 =>
				array(
					 'id' => (int) '19',
					'title' => 'Rio de Janeiro',
					 'country_id' => (int) '1',
					'letter' => 'RJ',
					'iso' => '33',
					'slug' => 'rio-de-janeiro',
					'population' => (int) '16635996',
				),
			19 =>
				array(
					 'id' => (int) '20',
					'title' => 'Rio Grande do Norte',
					 'country_id' => (int) '1',
					'letter' => 'RN',
					'iso' => '24',
					'slug' => 'rio-grande-do-norte',
					'population' => (int) '3474998',
				),
			20 =>
				array(
					 'id' => (int) '21',
					'title' => 'Rondônia',
					 'country_id' => (int) '1',
					'letter' => 'RO',
					'iso' => '11',
					'slug' => 'rondonia',
					'population' => (int) '1787279',
				),
			21 =>
				array(
					 'id' => (int) '22',
					'title' => 'Roraima',
					 'country_id' => (int) '1',
					'letter' => 'RR',
					'iso' => '14',
					'slug' => 'roraima',
					'population' => (int) '514229',
				),
			22 =>
				array(
					 'id' => (int) '23',
					'title' => 'Rio Grande do Sul',
					 'country_id' => (int) '1',
					'letter' => 'RS',
					'iso' => '43',
					'slug' => 'rio-grande-do-sul',
					'population' => (int) '11286500',
				),
			23 =>
				array(
					 'id' => (int) '24',
					'title' => 'Santa Catarina',
					 'country_id' => (int) '1',
					'letter' => 'SC',
					'iso' => '42',
					'slug' => 'santa-catarina',
					'population' => (int) '6910553',
				),
			24 =>
				array(
					 'id' => (int) '25',
					'title' => 'Sergipe',
					 'country_id' => (int) '1',
					'letter' => 'SE',
					'iso' => '28',
					'slug' => 'sergipe',
					'population' => (int) '2265779',
				),
			25 =>
				array(
					 'id' => (int) '26',
					'title' => 'São Paulo',
					 'country_id' => (int) '1',
					'letter' => 'SP',
					'iso' => '35',
					'slug' => 'sao-paulo',
					'population' => (int) '44749699',
				),
			26 =>
				array(
					 'id' => (int) '27',
					'title' => 'Tocantins',
					 'country_id' => (int) '1',
					'letter' => 'TO',
					'iso' => '17',
					'slug' => 'tocantins',
					'population' => (int) '1532902',
				),
		));
	}
}
