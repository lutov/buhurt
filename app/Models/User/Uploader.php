<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string element_type
 * @property int element_id
 * @property  int user_id
 */
class Uploader extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'uploaders_elements';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');

	}

}
