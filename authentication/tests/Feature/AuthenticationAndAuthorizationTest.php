<?php
namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthenticationAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_registered_user_is_created_with_default_user_role(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'john@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_unverified_user_cannot_open_dashboard(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/email/verify');
    }

    public function test_verified_user_can_open_dashboard(): void
    {
        $user = User::factory()->create(); 

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_verification_marks_email_as_verified(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect('/dashboard');
    }

    public function test_expired_or_invalid_verification_link_fails(): void
    {
        $user = User::factory()->unverified()->create();

        $invalidUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(1), 
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($invalidUrl);

        $response->assertStatus(403); 
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_regular_user_receives_403_on_admin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_open_admin_users(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::factory()->create();
        $admin->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
    }

    public function test_admin_can_change_another_users_role(): void
    {
        $permission = Permission::firstOrCreate([
            'name'  => 'manage_users',
            'label' => 'Manage Users',
        ]);

    
        \Illuminate\Support\Facades\Gate::define('manage_users', function (User $user) {
            return $user->hasPermission('manage_users');
        });

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'label' => 'Administrator']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator', 'label' => 'Moderator']);

        $adminRole->permissions()->syncWithoutDetaching([$permission->id]);

        $admin = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($adminRole->id);

        $targetUser = User::factory()->create();

        $response = $this->actingAs($admin->fresh(['roles.permissions']))
            ->patch(route('admin.update-role', $targetUser), [
                'role_id' => $moderatorRole->id,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302); 

        $this->assertTrue($targetUser->fresh()->roles->contains('id', $moderatorRole->id));
    }


    public function test_regular_user_receives_403_on_role_change_request(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();
        $moderatorRole = Role::where('name', 'moderator')->first();

        $response = $this->actingAs($user)->patch("/admin/{$targetUser->id}/role", [
            'role_id' => $moderatorRole->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_change_their_own_role(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        $admin = User::factory()->create();
        $admin->roles()->sync([$adminRole->id]);

        $response = $this->actingAs($admin)->patch("/admin/{$admin->id}/role", [
            'role_id' => $userRole->id,
        ]);

        $response->assertStatus(403);
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_forgot_password_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        Notification::assertSentTo(
            [$user],
            ResetPasswordNotification::class
        );
    }

    public function test_password_reset_with_valid_token_changes_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect('/login');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_password_reset_with_invalid_token_fails(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->post('/reset-password', [
            'token'                 => 'invalid-token-12345',
            'email'                 => $user->email,
            'password'              => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('OldPassword123!', $user->fresh()->password));
    }
}