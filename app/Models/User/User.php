<?php

namespace App\Models\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract {

    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function role() {

		return $this->hasOne('App\Models\User\Roleuser');

	}

	/**
	 * The roles that belong to the user.
	 */
	public function roles() {

		return $this->belongsToMany('App\Models\User\Role');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function rates() {

		return $this->hasMany('App\Models\User\Rate');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function options() {

		return $this->belongsToMany('App\Models\User\Option', 'options_users', 'user_id', 'option_id');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function wanted() {

		return $this->hasMany('App\Models\User\Wanted');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function notWanted() {

		return $this->hasMany('App\Models\User\NotWanted');

	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function achievements() {

		return $this->belongsToMany('App\Models\User\Achievement', 'achievements_users', 'user_id', 'achievement_id');

	}
}
