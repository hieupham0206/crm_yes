<?php

namespace Tests\Feature;

use App\Models\HistoryCall;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class HistoryCallTest extends TestCase {
	private $history_call;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->history_call = factory( HistoryCall::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_history_call() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'history_calls.index' ) )->assertStatus( 403 );

		$this->get( route( 'history_calls.create' ) )->assertStatus( 403 );

		$this->get( route( 'history_calls.edit', $this->history_call->id ) )->assertStatus( 403 );

		$this->get( route( 'history_calls.show', $this->history_call->id ) )->assertStatus( 403 );

		$this->post( route( 'history_calls.destroy', $this->history_call->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_history_call() {
		$this->authorizedUserSignIn();

		$this->get( route( 'history_calls.index' ) )->assertStatus( 200 );

		$this->get( route( 'history_calls.show', $this->history_call->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_history_call() {
		$this->authorizedUserSignIn();

		$this->get( route( 'history_calls.create' ) )->assertStatus( 200 );

		$history_call = make(HistoryCall::class);
		$this->post( route( 'history_calls.store' ), $history_call->toArray() )
		     ->assertRedirect( route( 'history_calls.index' ) );
	}

	public function test_authorized_user_can_edit_history_call() {
		$this->authorizedUserSignIn();

		$this->get( route( 'history_calls.edit', $this->history_call->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->history_call->title );

		$history_call = create(HistoryCall::class);

		$this->put( route( 'history_calls.update', $history_call->id ), $history_call->toArray())->assertRedirect( route( 'history_calls.index' ) );

		$this->assertDatabaseHas( 'history_calls', [
			'id' => $history_call->id
		] );
	}

	public function test_authorized_user_can_delete_history_call() {
		$this->authorizedUserSignIn();

		$history_call = create( HistoryCall::class );

		$this->delete( route( 'history_calls.destroy', $history_call->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'history_calls', [
			'id' => $history_call->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_history_call() {
  		$this->authorizedUserSignIn();

  		$history_call1 = create( HistoryCall::class, null );
  		$history_call2 = create( HistoryCall::class, null );

  		$this->delete( route( 'history_calls.destroys', [
  			'ids' => [$history_call1->id, $history_call2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'history_calls', [
  			'id' => $history_call1->id
  		] );

  		$this->assertSoftDeleted( 'history_calls', [
  			'id' => $history_call2->id
  		] );
  	}
}
