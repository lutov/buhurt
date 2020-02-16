<?php namespace App\Http\Controllers\Data;

use App\Http\Controllers\ElementController;

class MemesController extends ElementController {

	protected string $section = 'memes';
	protected bool $getSimilar = false;
	
}