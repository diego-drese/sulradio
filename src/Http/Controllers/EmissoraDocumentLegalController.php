<?php

namespace Oka6\SulRadio\Http\Controllers;

use Oka6\SulRadio\Models\Document;

class EmissoraDocumentLegalController extends EmissoraDocumentController {
	public $goal = Document::GOAL_LEGAL;
	
	
}