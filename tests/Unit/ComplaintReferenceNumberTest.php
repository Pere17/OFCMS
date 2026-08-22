<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintReferenceNumberTest extends TestCase
{
    use RefreshDatabase;

    public function test_reference_number_is_auto_generated_in_expected_format(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $complaint = Complaint::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subject' => 'Test Subject',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'medium',
        ]);

        $this->assertMatchesRegularExpression(
            '/^CMP-'.date('Y').'-\d{5}$/',
            $complaint->reference_number
        );
    }

    public function test_reference_numbers_increment_sequentially(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $first = Complaint::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subject' => 'First',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'low',
        ]);

        $second = Complaint::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subject' => 'Second',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'low',
        ]);

        $this->assertNotEquals($first->reference_number, $second->reference_number);
    }

    public function test_new_complaint_defaults_to_pending_status(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $complaint = Complaint::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'subject' => 'Test Subject',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'medium',
        ]);

        $this->assertEquals('pending', $complaint->status);
    }
}
