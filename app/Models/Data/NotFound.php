<?php namespace App\Models\Data;

use App\Traits\SectionTrait;
use Illuminate\Database\Eloquent\Model;

class NotFound extends Model {

	use SectionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'notfound';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function user() {

		return $this->hasOne('\App\Models\User\User');
	}

}
