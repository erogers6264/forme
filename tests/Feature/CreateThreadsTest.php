<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function guests_may_not_create_threads()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$thread = factory('App\Thread')->make();
		$this->post('/threads', $thread->toArray());
	}

	/** @test */
	function an_authenticated_user_can_create_new_forum_threads()
	{
		// Given we have a signed in user
		$this->actingAs($user = factory('App\User')->create());
		
		// When we hit the endpoint to create a new thread we create it
		$thread = factory('App\Thread')->make();
		$this->post('/threads', $thread->toArray());
		
		// When we visit the thread page we see the thread
		$this->get($thread->path())
			->assertSee($thread->title)
		    ->assertSee($thread->body);
	}
}
