<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Testes dos endpoints da API de livros.
 */
class LivroApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica se a API exige autenticação.
     */
    public function test_livros_endpoint_requires_authentication(): void
    {
        $response = $this->getJson('/api/livros');

        $response->assertUnauthorized();
    }

    /**
     * Verifica se um usuário autenticado pode listar livros.
     **/
    public function test_authenticated_user_can_list_livros(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        Livro::create([
            'LVRTITULO' => 'Quarta Asa',
            'LVRISBN' => '1234567890',
            'LVREDICAO' => 1,
            'LVRDTPUBLIC' => '2026-10-01',
            'LVRSINOPSE' => 'Guerras, Dragoes, politica e poderes. (Ugabuga)',
            'LVRFAIXAETARIA' => 18,
        ]);

        $response = $this->withToken($token)
            ->getJson('/api/livros');

        $response
            ->assertOk()
            ->assertJsonPath('data.0.codigo', 1)
            ->assertJsonPath('data.0.titulo', 'Quarta Asa')
            ->assertJsonPath('data.0.isbn', '1234567890')
            ->assertJsonPath('data.0.edicao', 1)
            ->assertJsonPath('data.0.data_publicacao', '2026-10-01')
            ->assertJsonPath('data.0.sinopse', 'Guerras, Dragoes, politica e poderes. (Ugabuga)')
            ->assertJsonPath('data.0.faixa_etaria', 18)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $response->assertJsonMissingPath('data.0.created_at');
        $response->assertJsonMissingPath('data.0.updated_at');
    }

    /**
     * Verifica se um usuário autenticado pode consultar um livro.
     */
    public function test_authenticated_user_can_show_a_livro(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $livro = Livro::create([
            'LVRTITULO' => 'Harry Potter',
            'LVRISBN' => '9780000000002',
            'LVREDICAO' => 2,
            'LVRDTPUBLIC' => '1997-06-26',
            'LVRSINOPSE' => 'Uma história de magia.',
            'LVRFAIXAETARIA' => 10,
        ]);

        $response = $this->withToken($token)
            ->getJson("/api/livros/{$livro->LVRCODIGO}");

        $response
            ->assertOk()
            ->assertJsonPath('data.codigo', $livro->LVRCODIGO)
            ->assertJsonPath('data.titulo', 'Harry Potter')
            ->assertJsonPath('data.isbn', '9780000000002');

        $response->assertJsonMissingPath('data.created_at');
        $response->assertJsonMissingPath('data.updated_at');
    }

    /**
     * Verifica se a API retorna 404 para um livro inexistente.
     */
    public function test_authenticated_user_gets_not_found_for_nonexistent_livro(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/livros/999999');

        $response->assertNotFound();
    }
}
