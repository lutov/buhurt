<?php namespace App\Models\Data;

use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use Eloquent;

class News extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use RatesTrait;
	use CommentsTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'news';

}
