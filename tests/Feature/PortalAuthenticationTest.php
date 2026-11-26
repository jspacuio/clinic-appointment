<?php

use App\Models\Doctor;
use App\Models\User;

test('admin login screen renders and authenticates administrators', function () {
    $admin = User::factory()->admin()->create();

    $this->get(route('admin.login'))->assertOk();

    $response = $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($admin);
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('doctor login screen renders and authenticates active doctors', function () {
    $doctorUser = User::factory()->doctor()->create();
    Doctor::factory()->for($doctorUser)->create(['is_active' => true]);

    $this->get(route('doctor.login'))->assertOk();

    $response = $this->post(route('doctor.login.store'), [
        'email' => $doctorUser->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($doctorUser);
    $response->assertRedirect(route('doctor.dashboard', absolute: false));
});

test('doctors cannot authenticate through the admin login page', function () {
    $doctorUser = User::factory()->doctor()->create();
    Doctor::factory()->for($doctorUser)->create();

    $response = $this->post(route('admin.login.store'), [
        'email' => $doctorUser->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('administrators cannot authenticate through the doctor login page', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->post(route('doctor.login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('inactive doctor cannot sign in to doctor portal', function () {
    $doctorUser = User::factory()->doctor()->inactive()->create();
    Doctor::factory()->for($doctorUser)->inactive()->create();

    $response = $this->post(route('doctor.login.store'), [
        'email' => $doctorUser->email,
        'password' => 'password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('guests are redirected to portal-specific login pages', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    $this->get(route('doctor.dashboard'))->assertRedirect(route('doctor.login'));
});

test('role middleware isolates admin and doctor portals', function () {
    $admin = User::factory()->admin()->create();
    $doctorUser = User::factory()->doctor()->create();
    Doctor::factory()->for($doctorUser)->create();

    $this->actingAs($doctorUser)
        ->get(route('admin.dashboard'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('doctor.dashboard'))
        ->assertForbidden();
});

test('portal logouts redirect to their respective login pages', function () {
    $admin = User::factory()->admin()->create();
    $doctorUser = User::factory()->doctor()->create();
    Doctor::factory()->for($doctorUser)->create();

    $this->actingAs($admin)
        ->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->actingAs($doctorUser)
        ->post(route('doctor.logout'))
        ->assertRedirect(route('doctor.login'));
});
