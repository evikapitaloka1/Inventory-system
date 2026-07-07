<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $role = Role::factory()->staff()->create();
        $user = User::factory()->create([
            'email' => 'test@inventaris.test',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@inventaris.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        User::factory()->staff()->create(['email' => 'test@inventaris.test']);

        $response = $this->post(route('login'), [
            'email' => 'test@inventaris.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_new_user_can_register_and_gets_staff_role(): void
    {
        Role::factory()->staff()->create();

        $response = $this->post(route('register'), [
            'name' => 'Mahasiswa Baru',
            'email' => 'baru@inventaris.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', ['email' => 'baru@inventaris.test']);

        $user = User::where('email', 'baru@inventaris.test')->first();
        $this->assertTrue($user->isStaff());
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->staff()->create();

        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_forgot_password_redirects_to_reset_form_for_registered_email(): void
    {
        $user = User::factory()->staff()->create(['email' => 'lupa@inventaris.test']);

        $response = $this->post(route('password.email'), ['email' => 'lupa@inventaris.test']);

        $response->assertRedirect();
        $response->assertSessionHas('email_prefill', 'lupa@inventaris.test');
    }

    public function test_forgot_password_shows_error_for_unregistered_email(): void
    {
        $response = $this->post(route('password.email'), ['email' => 'tidakada@inventaris.test']);

        $response->assertSessionHasErrors('email');
    }
}
