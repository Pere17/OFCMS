<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ComplaintSubmitted;
use Tests\TestCase;

class ComplaintSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_complainant_can_submit_a_valid_complaint(): void
    {
        Notification::fake();

        $complainant = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($complainant)->post('/complainant/complaints', [
            'category_id' => $category->id,
            'subject' => 'Water leakage in hostel block B',
            'description' => 'There has been a persistent water leakage in the corridor for a week.',
            'priority' => 'high',
        ]);

        $this->assertDatabaseHas('complaints', [
            'user_id' => $complainant->id,
            'subject' => 'Water leakage in hostel block B',
            'status' => 'pending',
        ]);

        $response->assertRedirect();

        Notification::assertSentTo($admin, ComplaintSubmitted::class);
    }

    public function test_complaint_submission_fails_with_missing_subject(): void
    {
        $complainant = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($complainant)->post('/complainant/complaints', [
            'category_id' => $category->id,
            'subject' => '',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'medium',
        ]);

        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_complaint_submission_fails_with_short_description(): void
    {
        $complainant = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($complainant)->post('/complainant/complaints', [
            'category_id' => $category->id,
            'subject' => 'Short description test',
            'description' => 'Too short',
            'priority' => 'medium',
        ]);

        $response->assertSessionHasErrors('description');
        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_complainant_cannot_view_another_users_complaint(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $category = Category::factory()->create();

        $complaint = Complaint::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'subject' => 'Private complaint',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($intruder)->get("/complainant/complaints/{$complaint->id}");

        $response->assertForbidden();
    }

    public function test_admin_resolving_a_complaint_sets_resolved_at_and_status(): void
    {
        Notification::fake();

        $complainant = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $complaint = Complaint::create([
            'user_id' => $complainant->id,
            'category_id' => $category->id,
            'subject' => 'Broken chair',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($admin)->post("/admin/complaints/{$complaint->id}/respond", [
            'message' => 'This has been fixed by maintenance.',
            'resolve' => '1',
        ]);

        $response->assertRedirect();

        $complaint->refresh();

        $this->assertEquals('resolved', $complaint->status);
        $this->assertNotNull($complaint->resolved_at);
        $this->assertDatabaseHas('responses', [
            'complaint_id' => $complaint->id,
            'admin_id' => $admin->id,
        ]);
    }
}
