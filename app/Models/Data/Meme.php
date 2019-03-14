<?php namespace App\Models\Data;

use App\Traits\CollectionsTrait;
use App\Traits\CommentsTrait;
use App\Traits\GenresTrait;
use App\Traits\NotwantedTrait;
use App\Traits\RatesTrait;
use App\Traits\SectionTrait;
use App\Traits\WantedTrait;
use Eloquent;

class Meme extends Eloquent  {

	use SectionTrait;
	use GenresTrait;
	use CollectionsTrait;
	use RatesTrait;
	use CommentsTrait;
	use WantedTrait;
	use NotwantedTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'memes';

	protected $morphClass = 'Meme';

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'alt_name', 'description', 'year', 'verified');

}
