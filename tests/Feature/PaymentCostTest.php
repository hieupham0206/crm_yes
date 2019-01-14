<?php

namespace Tests\Feature;

use App\Models\PaymentCost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentCostTest extends TestCase {
	private $payment_cost;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->payment_cost = factory( PaymentCost::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_payment_cost() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'payment_costs.index' ) )->assertStatus( 403 );

		$this->get( route( 'payment_costs.create' ) )->assertStatus( 403 );

		$this->get( route( 'payment_costs.edit', $this->payment_cost->id ) )->assertStatus( 403 );

		$this->get( route( 'payment_costs.show', $this->payment_cost->id ) )->assertStatus( 403 );

		$this->post( route( 'payment_costs.destroy', $this->payment_cost->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_payment_cost() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_costs.index' ) )->assertStatus( 200 );

		$this->get( route( 'payment_costs.show', $this->payment_cost->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_payment_cost() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_costs.create' ) )->assertStatus( 200 );

		$payment_cost = make(PaymentCost::class);
		$this->post( route( 'payment_costs.store' ), $payment_cost->toArray() )
		     ->assertRedirect( route( 'payment_costs.index' ) );
	}

	public function test_authorized_user_can_edit_payment_cost() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_costs.edit', $this->payment_cost->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->payment_cost->title );

		$payment_cost = create(PaymentCost::class);

		$this->put( route( 'payment_costs.update', $payment_cost->id ), $payment_cost->toArray())->assertRedirect( route( 'payment_costs.index' ) );

		$this->assertDatabaseHas( 'payment_costs', [
			'id' => $payment_cost->id
		] );
	}

	public function test_authorized_user_can_delete_payment_cost() {
		$this->authorizedUserSignIn();

		$payment_cost = create( PaymentCost::class );

		$this->delete( route( 'payment_costs.destroy', $payment_cost->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'payment_costs', [
			'id' => $payment_cost->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_payment_cost() {
  		$this->authorizedUserSignIn();

  		$payment_cost1 = create( PaymentCost::class, null );
  		$payment_cost2 = create( PaymentCost::class, null );

  		$this->delete( route( 'payment_costs.destroys', [
  			'ids' => [$payment_cost1->id, $payment_cost2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'payment_costs', [
  			'id' => $payment_cost1->id
  		] );

  		$this->assertSoftDeleted( 'payment_costs', [
  			'id' => $payment_cost2->id
  		] );
  	}
}
