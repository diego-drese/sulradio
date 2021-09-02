<?php

namespace Oka6\SulRadio\Http\Controllers;

use Oka6\SulRadio\Models\Document;

class EmissoraDocumentAdmController extends EmissoraDocumentController {
	public $goal = Document::GOAL_ADMIN;
	
	
}