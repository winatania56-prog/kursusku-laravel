<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_properly_with_true_pink_theme_and_toggle_password(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('id="togglePassword"', false);
        $response->assertSee('name="remember"', false);
        $response->assertSee(route('password.request'));
    }

    public function test_authenticated_admin_is_redirected_to_admin_dashboard_on_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@kursusku.test',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'admin@kursusku.test',
            'password' => 'AdminPass123!',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_user_is_redirected_to_user_dashboard_on_login(): void
    {
        $user = User::factory()->create([
            'email' => 'user@kursusku.test',
            'password' => Hash::make('UserPass123!'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'user@kursusku.test',
            'password' => 'UserPass123!',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'user@kursusku.test',
            'password' => Hash::make('UserPass123!'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'user@kursusku.test',
            'password' => 'WrongPassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_remember_me_functionality_sets_remember_token(): void
    {
        $user = User::factory()->create([
            'email' => 'remember@kursusku.test',
            'password' => Hash::make('UserPass123!'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'remember@kursusku.test',
            'password' => 'UserPass123!',
            'remember' => '1',
        ]);

        $this->assertAuthenticatedAs($user);
        $user->refresh();
        $this->assertNotNull($user->getRememberToken());
    }

    public function test_logout_clears_session_and_redirects_to_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    public function test_forgot_password_form_renders_properly(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.forgot-password');
        $response->assertSee('Lupa Password?');
        $response->assertSee('name="email"', false);
    }

    public function test_profile_page_displays_current_user_info(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@kursusku.test',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@kursusku.test');
    }

    public function test_profile_update_changes_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Lama',
            'email' => 'budi.lama@kursusku.test',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'name' => 'Budi Baru',
                'email' => 'budi.baru@kursusku.test',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Informasi profil berhasil diperbarui.');

        $user->refresh();
        $this->assertEquals('Budi Baru', $user->name);
        $this->assertEquals('budi.baru@kursusku.test', $user->email);
    }

    public function test_profile_update_password_verifies_current_password_and_hashes_new_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.password.update'), [
                'current_password' => 'OldPassword123!',
                'password' => 'NewSecurePassword123!',
                'password_confirmation' => 'NewSecurePassword123!',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('password_success', 'Password Anda berhasil diubah.');

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword123!', $user->password));
    }
}
