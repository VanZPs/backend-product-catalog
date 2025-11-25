<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SellerApproved;
use App\Notifications\SellerRejected;

class AdminSellerVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_approval_sends_signed_link_and_verify_marks_active()
    {
        Notification::fake();

        // create admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // create pending seller user
        $user = User::factory()->create(['role' => 'seller']);
        $seller = Seller::factory()->create(['user_id' => $user->user_id, 'status' => 'pending']);

        // Approve as admin
        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/dashboard/admin/sellers/' . $seller->seller_id . '/approve')
            ->assertStatus(200);

        // Notification sent
        Notification::assertSentTo($user, SellerApproved::class);

        // Now simulate clicking the signed URL (generate one as controller would)
        $signed = \URL::temporarySignedRoute('seller.verify', now()->addDays(7), ['seller' => $seller->seller_id]);

        $response = $this->getJson(parse_url($signed, PHP_URL_PATH) . '?' . parse_url($signed, PHP_URL_QUERY));
        $response->assertStatus(200);

        // Seller should be active and have verified_at
        $this->assertDatabaseHas('sellers', ['seller_id' => $seller->seller_id, 'is_active' => true, 'status' => 'active']);
    }

    public function test_admin_reject_archives_and_deletes_user_and_seller_and_sends_email()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'seller']);
        $seller = Seller::factory()->create(['user_id' => $user->user_id, 'status' => 'pending']);

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/dashboard/admin/sellers/' . $seller->seller_id . '/reject', ['reason' => 'Incomplete documents'])
            ->assertStatus(200);

        // Audit exists
        $this->assertDatabaseHas('seller_rejection_audits', ['seller_id' => $seller->seller_id]);

        // User and seller should be deleted
        $this->assertDatabaseMissing('sellers', ['seller_id' => $seller->seller_id]);
        $this->assertDatabaseMissing('users', ['user_id' => $user->user_id]);

        Notification::assertSentTo($user, SellerRejected::class);
    }
}
