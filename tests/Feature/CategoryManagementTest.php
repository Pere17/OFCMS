<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_a_category(): void
    {
        $superadmin = User::factory()->superadmin()->create();

        $response = $this->actingAs($superadmin)->post('/superadmin/categories', [
            'name' => 'Network Issues',
            'description' => 'Complaints about network connectivity.',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('superadmin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Network Issues']);
    }

    public function test_category_with_complaints_cannot_be_deleted(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $complainant = User::factory()->create();
        $category = Category::factory()->create();

        Complaint::create([
            'user_id' => $complainant->id,
            'category_id' => $category->id,
            'subject' => 'Test complaint',
            'description' => 'A description that is long enough to pass validation.',
            'priority' => 'low',
        ]);

        $response = $this->actingAs($superadmin)->delete("/superadmin/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_category_without_complaints_can_be_deleted(): void
    {
        $superadmin = User::factory()->superadmin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($superadmin)->delete("/superadmin/categories/{$category->id}");

        $response->assertRedirect(route('superadmin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
