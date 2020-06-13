<?php namespace App\Models\Data;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @property int|string order
 * @property int album_id
 */
class Track extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tracks';

	protected $morphClass = 'Track';

	public bool $verification = false;

	/**
	 * Record remains in the database, but marked with a special label
	 *
	 * @var boolean
	 */
	//protected $softDelete = true;

	protected $fillable = array('name', 'length', 'order', 'album_id');

	protected $visible = ['id', 'name'];

}