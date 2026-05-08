<?php

namespace Tests\Feature;

use App\Models\AssessmentAttempt;
use App\Models\User;
use Database\Seeders\AssessmentDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_interiology_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Kenali selera desain ruangan kamu di Interiology');
        $response->assertSee('Login');
        $response->assertDontSee('Akun');
    }

    public function test_guest_is_redirected_to_login_before_assessment(): void
    {
        $this->get('/assessment/start')->assertRedirect('/login');
    }

    public function test_user_registers_then_logs_in_manually(): void
    {
        $this->post('/register', [
            'name' => 'Ruang User',
            'email' => 'ruang@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/login');

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'ruang@example.com',
            'role' => 'user',
        ]);

        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Selamat datang kembali!')
            ->assertDontSee('ruang@example.com');

        $this->post('/login', [
            'login' => 'Ruang User',
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();

        $this->get('/profile')
            ->assertStatus(200)
            ->assertSee('Ruang User');
    }

    public function test_admin_can_see_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Ruang',
            'role' => 'admin',
        ]);

        User::factory()->count(2)->create();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('Akun Terbaru')
            ->assertSee('Admin Ruang');
    }

    public function test_assessment_flow_calculates_result_on_backend(): void
    {
        $this->seed(AssessmentDataSeeder::class);

        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/assessment/start')->assertRedirect('/assessment');

        for ($i = 0; $i < 10; $i++) {
            $response = $this->post('/assessment', ['option' => 2]);

            if ($i < 9) {
                $response->assertRedirect('/assessment');
            } else {
                $response->assertRedirect('/result');
            }
        }

        $this->get('/result')
            ->assertStatus(200)
            ->assertSee('Si Elegan Modern');

        $this->assertDatabaseHas('assessment_attempts', [
            'user_id' => $user->id,
            'result_key' => 'modern',
        ]);
        $this->assertSame(1, AssessmentAttempt::count());
    }

    public function test_legacy_static_urls_redirect_to_laravel_routes(): void
    {
        $this->get('/HomePage.html')->assertRedirect('/');
        $this->get('/PreparePage.html')->assertRedirect('/prepare');
    }
}
