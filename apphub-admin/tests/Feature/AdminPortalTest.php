<?php

namespace Tests\Feature;

use App\Models\BusinessCategory;
use App\Models\MApps;
use App\Models\User;
use App\Models\WebsiteType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function as(string $email): static
    {
        return $this->actingAs(User::where('email', $email)->firstOrFail());
    }

    public function test_demo_data_is_seeded(): void
    {
        $this->assertSame(14, MApps::count());
        $this->assertSame(6, BusinessCategory::count());
        $this->assertSame(3, WebsiteType::count());
        $this->assertTrue(User::where('email', 'admin@example.com')->first()->hasRole('superadmin'));
    }

    public function test_guests_are_sent_to_login_and_cannot_register(): void
    {
        $this->get('/list')->assertRedirect('/login');
        $this->get('/user')->assertRedirect('/login');
        $this->get('/register')->assertNotFound();
        $this->post('/register', ['name' => 'x', 'email' => 'x@example.com', 'password' => 'Password#123', 'password_confirmation' => 'Password#123'])->assertStatus(404);
    }

    public function test_login_page_has_no_company_branding_and_shows_demo_hint(): void
    {
        config(['app.demo' => true]);

        $this->get('/login')->assertOk()->assertSee('AppHub')->assertSee('admin@example.com')->assertDontSee('MIND', false);
    }

    public function test_menu_access_follows_roles(): void
    {
        // A plain user reaches home and profile only
        $this->as('viewer@example.com')->get('/home')->assertOk();
        foreach (['/list', '/user', '/role', '/website-type', '/business-categories'] as $path) {
            $this->as('viewer@example.com')->get($path)->assertForbidden();
        }

        // Admin manages content and users but not roles
        foreach (['/list', '/user', '/website-type', '/business-categories'] as $path) {
            $this->as('editor@example.com')->get($path)->assertOk();
        }
        $this->as('editor@example.com')->get('/role')->assertForbidden();

        // Superadmin sees everything
        $this->as('admin@example.com')->get('/role')->assertOk();
    }

    public function test_profile_page_renders(): void
    {
        $this->as('viewer@example.com')->get('/profile')->assertOk();
    }

    public function test_content_list_shows_every_logo_as_a_data_uri(): void
    {
        $html = $this->as('admin@example.com')->get('/list')->assertOk()->getContent();

        $this->assertStringContainsString('data:image/svg+xml;base64,', $html);
        $this->assertStringContainsString('Procure Pro', $html);
    }

    public function test_admin_can_create_edit_toggle_and_delete_an_app(): void
    {
        $type = WebsiteType::first();
        $cats = BusinessCategory::take(2)->pluck('id')->all();
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        $file = UploadedFile::fake()->createWithContent('logo.png', $png);

        $this->as('editor@example.com')->post('/list/store', [
            'nama' => 'Demo Portal', 'deskripsi' => 'A test app', 'link_aplikasi' => 'https://apps.example.com/demo',
            'website_type_id' => $type->id, 'business_category_id' => $cats, 'logo' => $file,
        ])->assertRedirect(route('list'));

        $app = MApps::where('app_name', 'Demo Portal')->firstOrFail();
        $this->assertSame(2, $app->businessCategories()->count());
        $this->assertNotEmpty($app->app_logo);

        $html = $this->as('editor@example.com')->get('/list?search=demo')->assertOk()->getContent();
        $this->assertStringContainsString('data:image/png;base64,', $html);

        $this->as('editor@example.com')->put("/list/{$app->id}/update", [
            'nama' => 'Demo Portal 2', 'deskripsi' => 'Changed', 'link_aplikasi' => 'https://apps.example.com/demo2',
            'website_type_id' => $type->id, 'business_category_id' => [$cats[0]],
        ])->assertRedirect(route('list'));
        $this->assertSame('Demo Portal 2', $app->fresh()->app_name);
        $this->assertSame(1, $app->businessCategories()->count());

        $this->as('editor@example.com')->put("/list/{$app->id}/toggle-status")->assertRedirect();
        $this->assertSame('inactive', $app->fresh()->status);

        $this->as('editor@example.com')->delete("/list/{$app->id}/delete")->assertRedirect(route('list'));
        $this->assertNull(MApps::find($app->id));
    }

    public function test_viewers_cannot_write_content(): void
    {
        $app = MApps::first();

        $this->as('viewer@example.com')->delete("/list/{$app->id}/delete")->assertForbidden();
        $this->as('viewer@example.com')->post('/list/store', ['nama' => 'Nope'])->assertForbidden();
        $this->assertNotNull($app->fresh());
    }

    public function test_content_form_rejects_bad_input(): void
    {
        $this->as('editor@example.com')->post('/list/store', ['nama' => '', 'link_aplikasi' => 'not a url'])
            ->assertSessionHasErrors(['nama', 'link_aplikasi', 'website_type_id', 'business_category_id']);
    }

    public function test_role_and_user_crud_redirect_back_to_their_lists(): void
    {
        $this->as('admin@example.com')->post('/role', ['name' => 'auditor', 'guard_name' => 'web'])
            ->assertRedirect('/role')->assertSessionHas('success');

        $this->as('admin@example.com')->post('/user/store', [
            'name' => 'Pengguna Baru',
            'email' => 'baru@example.com',
            'password' => 'Rahasia#12345',
            'password_confirmation' => 'Rahasia#12345',
            'status' => 'active',
        ])->assertRedirect('/user')->assertSessionHas('success');

        $id = User::where('email', 'baru@example.com')->value('id');
        $this->as('admin@example.com')->delete("/user/{$id}")->assertRedirect('/user');
    }

    public function test_sso_routes_are_disabled(): void
    {
        $this->get('/login/azure')->assertNotFound();
        $this->get('/login/azure/callback')->assertNotFound();
    }
}
