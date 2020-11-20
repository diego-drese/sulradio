<?php

namespace Oka6\SulRadio\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ContatoInfo extends Model {
	const TABLE = 'contato_info';
	public $timestamps = false;
	protected $fillable = [
		'contato_infoID',
		'fone_contato',
		'fax_contato',
		'cel_contato',
		'email_contato',
		'contatoID',
	];
	protected $connection = 'sulradio';
	protected $table = 'contato_info';
	protected $primaryKey = 'contato_infoID';
	
	public static function insertOrupdateContacts(Request $request, $contactId) {
		self::where('contatoID', $contactId)->delete();
		$length = count($request->get('fone_contato'));
		for ($i=0; $i<$length; $i++){
			$telefone = $request->get('fone_contato')[$i];
			$fax = $request->get('fax_contato')[$i];
			$cel = $request->get('cel_contato')[$i];
			$email = $request->get('email_contato')[$i];
			if($telefone || $fax || $cel ||$email){
				self::create([
					'fone_contato'=>$telefone,
					'fax_contato'=>$fax,
					'cel_contato'=>$cel,
					'email_contato'=>$email,
					'contatoID'=>$contactId,
				]);
				
			}
		}
	}
	public static function getByContactId($contatoID){
		return self::where('contatoID', $contatoID)->get();
	}
	
}
