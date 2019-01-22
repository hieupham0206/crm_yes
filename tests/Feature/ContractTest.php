<?php

namespace Tests\Feature;

use App\Models\Contract;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ContractTest extends TestCase {
	private $contract;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

//		$this->contract = factory( Contract::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_contract() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'contracts.index' ) )->assertStatus( 403 );

		$this->get( route( 'contracts.create' ) )->assertStatus( 403 );

		$this->get( route( 'contracts.edit', $this->contract->id ) )->assertStatus( 403 );

		$this->get( route( 'contracts.show', $this->contract->id ) )->assertStatus( 403 );

		$this->post( route( 'contracts.destroy', $this->contract->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_contract() {
		$this->authorizedUserSignIn();

		$this->get( route( 'contracts.index' ) )->assertStatus( 200 );

		$this->get( route( 'contracts.show', $this->contract->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_contract() {
		$this->authorizedUserSignIn();

		$this->get( route( 'contracts.create' ) )->assertStatus( 200 );

		$contract = make(Contract::class);
		$this->post( route( 'contracts.store' ), $contract->toArray() )
		     ->assertRedirect( route( 'contracts.index' ) );
	}

	public function test_authorized_user_can_edit_contract() {
		$this->authorizedUserSignIn();

		$this->get( route( 'contracts.edit', $this->contract->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->contract->title );

		$contract = create(Contract::class);

		$this->put( route( 'contracts.update', $contract->id ), $contract->toArray())->assertRedirect( route( 'contracts.index' ) );

		$this->assertDatabaseHas( 'contracts', [
			'id' => $contract->id
		] );
	}

	public function test_authorized_user_can_delete_contract() {
		$this->authorizedUserSignIn();

		$contract = create( Contract::class );

		$this->delete( route( 'contracts.destroy', $contract->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'contracts', [
			'id' => $contract->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_contract() {
  		$this->authorizedUserSignIn();

  		$contract1 = create( Contract::class, null );
  		$contract2 = create( Contract::class, null );

  		$this->delete( route( 'contracts.destroys', [
  			'ids' => [$contract1->id, $contract2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'contracts', [
  			'id' => $contract1->id
  		] );

  		$this->assertSoftDeleted( 'contracts', [
  			'id' => $contract2->id
  		] );
  	}
}
