<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        //
		Relation::morphMap([
			'Book' => 'App\Models\Book',
			'Film' => 'App\Models\Film',
			'Game' => 'App\Models\Game',
			'Album' => 'App\Models\Album',
			'Meme' => 'App\Models\Meme',
		]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
	public function register()
	{
		if ($this->app->environment() !== 'production') {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
		}
		// ...
	}
}
