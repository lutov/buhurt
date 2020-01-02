<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BooksTest extends TestCase
{

	/** @test  */
	public function a_user_can_browse_books()
	{

		$book = factory('App\Book')->create();

		$response = $this->get('/books');

		$response->assertSee($book->name);

	}

}
