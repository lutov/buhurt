<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BooksTest extends TestCase
{

	/** @test  */
	public function a_user_can_browse_books(): void
	{
		$response = $this->get('/books');
		$response->assertStatus(200);
	}

}
