<?php

namespace Tests\Feature;

use App\Models\Appointment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AppointmentTest extends TestCase {
	private $appointment;

	use DatabaseMigrations;

	protected function setUp() {
		parent::setUp();

		$this->appointment = factory( Appointment::class )->create();
	}

	public function test_unauthorized_user_cannot_crud_appointment() {
		$this->withExceptionHandling()->unauthorizedUserSignIn();

		$this->get( route( 'appointments.index' ) )->assertStatus( 403 );

		$this->get( route( 'appointments.create' ) )->assertStatus( 403 );

		$this->get( route( 'appointments.edit', $this->appointment->id ) )->assertStatus( 403 );

		$this->get( route( 'appointments.show', $this->appointment->id ) )->assertStatus( 403 );

		$this->post( route( 'appointments.destroy', $this->appointment->id ), ['_method' => 'delete'] )->assertStatus( 403 );
	}

	public function test_authorized_user_can_read_appointment() {
		$this->authorizedUserSignIn();

		$this->get( route( 'appointments.index' ) )->assertStatus( 200 );

		$this->get( route( 'appointments.show', $this->appointment->id ) )->assertStatus( 200 );
	}

	public function test_authorized_user_can_create_appointment() {
		$this->authorizedUserSignIn();

		$this->get( route( 'appointments.create' ) )->assertStatus( 200 );

		$appointment = make(Appointment::class);
		$this->post( route( 'appointments.store' ), $appointment->toArray() )
		     ->assertRedirect( route( 'appointments.index' ) );
	}

	public function test_authorized_user_can_edit_appointment() {
		$this->authorizedUserSignIn();

		$this->get( route( 'appointments.edit', $this->appointment->id ) )
		     ->assertStatus( 200 )
		     ->assertSee( $this->appointment->title );

		$appointment = create(Appointment::class);

		$this->put( route( 'appointments.update', $appointment->id ), $appointment->toArray())->assertRedirect( route( 'appointments.index' ) );

		$this->assertDatabaseHas( 'appointments', [
			'id' => $appointment->id
		] );
	}

	public function test_authorized_user_can_delete_appointment() {
		$this->authorizedUserSignIn();

		$appointment = create( Appointment::class );

		$this->delete( route( 'appointments.destroy', $appointment->id ))->assertStatus( 200 );

		$this->assertSoftDeleted( 'appointments', [
			'id' => $appointment->id
		] );
	}

	public function test_authorized_user_can_delete_multiple_appointment() {
  		$this->authorizedUserSignIn();

  		$appointment1 = create( Appointment::class, null );
  		$appointment2 = create( Appointment::class, null );

  		$this->delete( route( 'appointments.destroys', [
  			'ids' => [$appointment1->id, $appointment2->id]
  		] ) )->assertStatus( 200 );

  		$this->assertSoftDeleted( 'appointments', [
  			'id' => $appointment1->id
  		] );

  		$this->assertSoftDeleted( 'appointments', [
  			'id' => $appointment2->id
  		] );
  	}
}
