<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ApplicationSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_accessible(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/forgot-password')->assertOk();
    }

    public function test_guests_are_redirected_from_protected_routes(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/categorias')->assertRedirect('/login');
        $this->get('/credenciales')->assertRedirect('/login');
        $this->get('/perfil')->assertRedirect('/login');
        $this->get('/informe/pdf')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_main_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Bienvenido, '.$user->name);

        $this->actingAs($user)
            ->get('/categorias')
            ->assertOk()
            ->assertSee("new DataTable('#tablaCategorias'", false);

        $this->actingAs($user)
            ->get('/credenciales')
            ->assertOk()
            ->assertSee("new DataTable('#tablaCredenciales'", false);

        $this->actingAs($user)
            ->get('/perfil')
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false);

        $this->actingAs($user)->get('/herramientas/verificar-password')->assertOk();
    }

    public function test_user_profile_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/perfil', [
                'name' => 'Perfil Actualizado',
                'email' => 'perfil@example.com',
                'telefono' => '+1 555 0100',
            ])
            ->assertRedirect(route('perfil.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Perfil Actualizado',
            'email' => 'perfil@example.com',
        ]);
    }

    public function test_password_reset_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHas('success');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect(route('landing'));

        $this->assertGuest();
    }

    public function test_api_endpoints_return_json(): void
    {
        $this->getJson('/api/v1/estadisticas')
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonStructure([
                'status',
                'data' => ['total_bovedas', 'total_credenciales', 'por_boveda'],
            ]);

        $this->getJson('/api/v1/bovedas')
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonStructure(['status', 'total', 'data']);
    }

    public function test_pdf_report_is_downloadable(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/informe/pdf')
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
