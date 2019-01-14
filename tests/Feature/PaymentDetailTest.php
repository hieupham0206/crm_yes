<?php

namespace Tests\Feature;

use App\Models\PaymentDetail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentDetailTest extends TestCase {
	private $payment_detail;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->payment_detail = factory( PaymentDetail::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_payment_detail() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'payment_details.index' ) )->assertStatus( 403 );

		$this->get( route( 'payment_details.create' ) )->assertStatus( 403 );

		$this->get( route( 'payment_details.edit', $this->payment_detail->id ) )->assertStatus( 403 );

		$this->get( route( 'payment_details.show', $this->payment_detail->id ) )->assertStatus( 403 );

		$this->post( route( 'payment_details.destroy', $this->payment_detail->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_payment_detail() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_details.index' ) )->assertStatus( 200 );

		$this->get( route( 'payment_details.show', $this->payment_detail->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_payment_detail() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_details.create' ) )->assertStatus( 200 );

		$payment_detail = make(PaymentDetail::class);
		$this->post( route( 'payment_details.store' ), $payment_detail->toArray() )
		     ->assertRedirect( route( 'payment_details.index' ) );
	}

	public function test_authorized_user_can_edit_payment_detail() {
		$this->authorizedUserSignIn();

		$this->get( route( 'payment_details.edit', $this->payment_detail->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->payment_detail->title );

		$payment_detail = create(PaymentDetail::class);

		$this->put( route( 'payment_details.update', $payment_detail->id ), $payment_detail->toArray())->assertRedirect( route( 'payment_details.index' ) );

		$this->assertDatabaseHas( 'payment_details', [
			'id' => $payment_detail->id
		] );
	}

	public function test_authorized_user_can_delete_payment_detail() {
		$this->authorizedUserSignIn();

		$payment_detail = create( PaymentDetail::class );

		$this->delete( route( 'payment_details.destroy', $payment_detail->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'payment_details', [
			'id' => $payment_detail->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_payment_detail() {
  		$this->authorizedUserSignIn();

  		$payment_detail1 = create( PaymentDetail::class, null );
  		$payment_detail2 = create( PaymentDetail::class, null );

  		$this->delete( route( 'payment_details.destroys', [
  			'ids' => [$payment_detail1->id, $payment_detail2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'payment_details', [
  			'id' => $payment_detail1->id
  		] );

  		$this->assertSoftDeleted( 'payment_details', [
  			'id' => $payment_detail2->id
  		] );
  	}
}
