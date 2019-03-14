<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string event_type
 * @property mixed element_type
 * @property int element_id
 * @property int user_id
 * @property mixed name
 * @property string text
 */
class Event extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';

	//public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function element() {

		return $this->morphTo();

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {

		return $this->belongsTo('App\Models\User\User');
	}

}
