<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_sends_reset_link_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'user@kursusku.test',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'user@kursusku.test',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_forgot_password_fails_for_non_existent_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'tidakada@kursusku.test',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_reset_password_page_renders_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'user@kursusku.test',
        ]);

        $token = Password::createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => 'user@kursusku.test']));

        $response->assertStatus(200);
        $response->assertViewIs('auth.reset-password');
        $response->assertSee('Buat Password Baru');
        $response->assertSee($token);
    }

    public function test_reset_password_updates_user_password_and_redirects_to_login(): void
    {
        $user = User::factory()->create([
            'email' => 'resetuser@kursusku.test',
            'password' => Hash::make('OldSecretPassword123!'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'resetuser@kursusku.test',
            'password' => 'NewResetPassword123!',
            'password_confirmation' => 'NewResetPassword123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('NewResetPassword123!', $user->password));
    }
}
