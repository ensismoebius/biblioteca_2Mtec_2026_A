<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    /** Git Olhou pra mim e disse q tem q fazer a documentação: Essa função garante que um visitante não consiga acessar diretamente a página de perfil. */
    public function test_guest_cannot_access_profile_page_directly(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect(route('login'));
    }

    /** Git Olhou pra mim e disse q tem q fazer a documentação2: Essa função garante que um visitante não consiga atualizar o perfil diretamente. */
    public function test_guest_cannot_update_profile_directly(): void
    {
        $response = $this->patch('/profile', [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** Git Olhou pra mim e disse q tem q fazer a documentação3: Essa função garante que um visitante não consiga excluir a própria conta diretamente. */
    public function test_guest_cannot_delete_account_directly(): void
    {
        $response = $this->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertRedirect(route('login'));
    }
}
