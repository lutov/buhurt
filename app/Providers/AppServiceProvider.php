<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
		Relation::morphMap([
			'Book' => 'App\Models\Data\Book',
			'Film' => 'App\Models\Data\Film',
			'Game' => 'App\Models\Data\Game',
			'Album' => 'App\Models\Data\Album',
			'Meme' => 'App\Models\Data\Meme',
		]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
	public function register() {

		if($this->app->environment() !== 'production') {
			//
		}

	}
}
