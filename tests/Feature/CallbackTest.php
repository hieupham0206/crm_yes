<?php

namespace Tests\Feature;

use App\Models\Callback;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CallbackTest extends TestCase {
	private $callback;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->callback = factory( Callback::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_callback() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'callbacks.index' ) )->assertStatus( 403 );

		$this->get( route( 'callbacks.create' ) )->assertStatus( 403 );

		$this->get( route( 'callbacks.edit', $this->callback->id ) )->assertStatus( 403 );

		$this->get( route( 'callbacks.show', $this->callback->id ) )->assertStatus( 403 );

		$this->post( route( 'callbacks.destroy', $this->callback->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_callback() {
		$this->authorizedUserSignIn();

		$this->get( route( 'callbacks.index' ) )->assertStatus( 200 );

		$this->get( route( 'callbacks.show', $this->callback->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_callback() {
		$this->authorizedUserSignIn();

		$this->get( route( 'callbacks.create' ) )->assertStatus( 200 );

		$callback = make(Callback::class);
		$this->post( route( 'callbacks.store' ), $callback->toArray() )
		     ->assertRedirect( route( 'callbacks.index' ) );
	}

	public function test_authorized_user_can_edit_callback() {
		$this->authorizedUserSignIn();

		$this->get( route( 'callbacks.edit', $this->callback->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->callback->title );

		$callback = create(Callback::class);

		$this->put( route( 'callbacks.update', $callback->id ), $callback->toArray())->assertRedirect( route( 'callbacks.index' ) );

		$this->assertDatabaseHas( 'callbacks', [
			'id' => $callback->id
		] );
	}

	public function test_authorized_user_can_delete_callback() {
		$this->authorizedUserSignIn();

		$callback = create( Callback::class );

		$this->delete( route( 'callbacks.destroy', $callback->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'callbacks', [
			'id' => $callback->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_callback() {
  		$this->authorizedUserSignIn();

  		$callback1 = create( Callback::class, null );
  		$callback2 = create( Callback::class, null );

  		$this->delete( route( 'callbacks.destroys', [
  			'ids' => [$callback1->id, $callback2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'callbacks', [
  			'id' => $callback1->id
  		] );

  		$this->assertSoftDeleted( 'callbacks', [
  			'id' => $callback2->id
  		] );
  	}
}
