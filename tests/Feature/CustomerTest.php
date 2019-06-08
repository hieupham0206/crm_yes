<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomerTest extends TestCase {
	private $customer;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->customer = factory( Customer::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_customer() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'customers.index' ) )->assertStatus( 403 );

		$this->get( route( 'customers.create' ) )->assertStatus( 403 );

		$this->get( route( 'customers.edit', $this->customer ) )->assertStatus( 403 );

		$this->get( route( 'customers.show', $this->customer ) )->assertStatus( 403 );

		$this->post( route( 'customers.destroy', $this->customer ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_customer() {
		$this->authorizedUserSignIn();

		$this->get( route( 'customers.index' ) )->assertStatus( 200 );

		$this->get( route( 'customers.show', $this->customer ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_customer() {
		$this->authorizedUserSignIn();

		$this->get( route( 'customers.create' ) )->assertStatus( 200 );

		$customer = make(Customer::class);
		$this->post( route( 'customers.store' ), $customer->toArray() )
		     ->assertRedirect( route( 'customers.index' ) );
	}

	public function test_authorized_user_can_edit_customer() {
		$this->authorizedUserSignIn();

		$this->get( route( 'customers.edit', $this->customer ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->customer->title );

		$customer = create(Customer::class);

		$this->put( route( 'customers.update', $customer->id ), $customer->toArray())->assertRedirect( route( 'customers.index' ) );

		$this->assertDatabaseHas( 'customers', [
			'id' => $customer->id
		] );
	}

	public function test_authorized_user_can_delete_customer() {
		$this->authorizedUserSignIn();

		$customer = create( Customer::class );

		$this->delete( route( 'customers.destroy', $customer->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'customers', [
			'id' => $customer->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_customer() {
  		$this->authorizedUserSignIn();

  		$customer1 = create( Customer::class, null );
  		$customer2 = create( Customer::class, null );

  		$this->delete( route( 'customers.destroys', [
  			'ids' => [$customer1->id, $customer2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'customers', [
  			'id' => $customer1->id
  		] );

  		$this->assertSoftDeleted( 'customers', [
  			'id' => $customer2->id
  		] );
  	}
}
