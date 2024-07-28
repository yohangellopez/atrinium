<?php

namespace Tests\Feature;

use App\Models\ActivityType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function an_admin_can_view_all_companies_with_pagination()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Company::factory()->count(30)->create();

        $response = $this->actingAs(User::find($admin->id), 'sanctum')
            ->getJson('/api/companies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'companies' => [
                    'data' => [
                        '*' => ['id', 'name', 'document_type', 'status', 'created_at', 'updated_at'],
                    ],
                    'links',
                ]
            ]);
    }

    /** @test */
    public function a_non_admin_can_only_view_own_companies_with_pagination()
    {
        $user = User::factory()->create();
        $user->assignRole('normal');

        $ownCompany = Company::factory()->create(['user_id' => $user->id]);
        $otherCompany = Company::factory()->create();

        $response = $this->actingAs(User::find($user->id), 'sanctum')
            ->getJson('/api/companies');

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $ownCompany->id])
            ->assertJsonMissing(['id' => $otherCompany->id]);
    }

    /** @test */
    public function a_user_can_store_a_company()
    {
        $user = User::factory()->create();
        $user->assignRole('normal');

        $companyData = [
            'name' => 'Test Company',
            'contact_email' => 'test_company@example.com',
            'document_type' => 'dni',
            'document_number' => '12345678',
            'status' => 'active',
        ];

        $response = $this->actingAs(User::find($user->id), 'sanctum')
            ->postJson('/api/companies', $companyData);

        $response->assertStatus(200)
            ->assertJson(['company' => $companyData]);

        $this->assertDatabaseHas('companies', ['name' => 'Test Company']);
    }

    /** @test */
    public function a_user_can_associate_activity_to_own_company()
    {
        $user = User::factory()->create();
        $user->assignRole('normal');
        $company = Company::factory()->create(['user_id' => $user->id]);
        $activityType = ActivityType::factory()->create();

        $response = $this->actingAs(User::find($user->id), 'sanctum')
            ->postJson("/api/companies/{$company->id}/associate-activity", ['activity_type_id' => $activityType->id]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Activity type associated successfully.']);

        $this->assertDatabaseHas('company_activity_type', [
            'company_id' => $company->id,
            'activity_type_id' => $activityType->id
        ]);
    }

    /** @test */
    public function a_user_can_dissociate_activity_from_own_company()
    {
        $user = User::factory()->create();
        $user->assignRole('normal');
        $company = Company::factory()->create(['user_id' => $user->id]);
        $activityType = ActivityType::factory()->create();
        $company->activityTypes()->attach($activityType->id);

        $response = $this->actingAs(User::find($user->id), 'sanctum')
            ->postJson("/api/companies/{$company->id}/dissociate-activity", ['activity_type_id' => $activityType->id]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Activity type dissociated successfully.']);

        $this->assertDatabaseMissing('company_activity_type', [
            'company_id' => $company->id,
            'activity_type_id' => $activityType->id
        ]);
    }
}
