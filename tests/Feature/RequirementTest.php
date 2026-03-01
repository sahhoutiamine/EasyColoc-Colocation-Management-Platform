<?php

namespace Tests\Feature;

use App\Models\Colocation;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequirementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_cannot_cancel_if_members_exist(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()]);
        Membership::create(['user_id' => $member->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()]);

        $response = $this->actingAs($owner)->delete(route('colocations.destroy', $colocation));
        
        $response->assertSessionHas('error', 'You cannot cancel the colocation while there are still other members. Please remove them or ask them to leave first.');
        $this->assertEquals('ACTIVE', $colocation->refresh()->status);
    }

    public function test_owner_can_cancel_if_no_members_exist(): void
    {
        $owner = User::factory()->create();
        $colocation = Colocation::create(['name' => 'Test Coloc', 'status' => 'ACTIVE']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()]);

        $response = $this->actingAs($owner)->delete(route('colocations.destroy', $colocation));
        
        $this->assertEquals('CANCELLED', $colocation->refresh()->status);
    }

    public function test_owner_transition_on_ban(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $owner = User::factory()->create(['name' => 'Banned Owner']);
        $member1 = User::factory()->create(['name' => 'Early Member']);
        $member2 = User::factory()->create(['name' => 'Late Member']);
        
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()->subDays(10)]);
        Membership::create(['user_id' => $member1->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()->subDays(5)]);
        Membership::create(['user_id' => $member2->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()->subDays(2)]);

        // Admin bans owner
        $this->actingAs($admin)->post(route('admin.users.ban', $owner));

        $this->assertTrue($owner->refresh()->is_banned);
        
        // member1 should be the new owner
        $newOwnerMembership = Membership::where('user_id', $member1->id)->first();
        $this->assertEquals('OWNER', $newOwnerMembership->role);
        
        // banned user should have left the colocation
        $oldOwnerMembership = Membership::where('user_id', $owner->id)->first();
        $this->assertNotNull($oldOwnerMembership->left);
    }

    public function test_colocation_cancelled_on_ban_if_no_successor(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $owner = User::factory()->create(['name' => 'Banned Only Member']);
        
        $colocation = Colocation::create(['name' => 'Test Coloc', 'status' => 'ACTIVE']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()->subDays(10)]);

        // Admin bans owner
        $this->actingAs($admin)->post(route('admin.users.ban', $owner));

        $this->assertTrue($owner->refresh()->is_banned);
        $this->assertEquals('CANCELLED', $colocation->refresh()->status);
        $this->assertNotNull(Membership::where('user_id', $owner->id)->first()->left);
    }

    public function test_member_kicked_on_ban(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $owner = User::factory()->create(['name' => 'Owner']);
        $member = User::factory()->create(['name' => 'Banned Member']);
        
        $colocation = Colocation::create(['name' => 'Test Coloc', 'status' => 'ACTIVE']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()->subDays(10)]);
        Membership::create(['user_id' => $member->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()->subDays(5)]);

        // Admin bans member
        $this->actingAs($admin)->post(route('admin.users.ban', $member));

        $this->assertTrue($member->refresh()->is_banned);
        $this->assertNotNull(Membership::where('user_id', $member->id)->first()->left);
        $this->assertEquals('OWNER', Membership::where('user_id', $owner->id)->first()->role);
        $this->assertEquals('ACTIVE', $colocation->refresh()->status);
    }
}
