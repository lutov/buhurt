<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
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

	public function role()
	{
		return $this->hasOne('App\Models\Roleuser');
	}

	/**
	 * The roles that belong to the user.
	 */
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role');
	}

	/**
	 * Отношение с оценками книг
	 */
	public function rates()
	{
		return $this->hasMany('App\Models\Rate');
	}

	/**
	 * Отношение с оценками книг
	 */
	public function options()
	{
		return $this->belongsToMany('App\Models\Option', 'options_users', 'user_id', 'option_id');
	}

	/**
	 * Отношение сo списком желаемых книг
	 */
	public function wanted()
	{
		return $this->hasMany('App\Models\Wanted');
	}

	/**
	 * Отношение сo списком нежеланных книг
	 */
	public function notWanted()
	{
		return $this->hasMany('App\Models\NotWanted');
	}

	/**
	 * Отношение с достижениями
	 */
	public function achievements()
	{
		return $this->belongsToMany('App\Models\Achievement', 'achievements_users', 'user_id', 'achievement_id');
	}
}
