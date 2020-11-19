<?php
namespace Oka6\SulRadio\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;

class SulradioController extends BaseController {
	public $parameters = [];
	
    public function renderView($view, $extraParameter=[]){
    	$this->makeParameters($extraParameter);
		$parameters = array_merge($this->parameters, $extraParameter);
	    return view($view, $parameters);
    }
    protected function makeParameters($extraParameter = null){
    
    }
}