<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ServiceType;
use App\Models\Budget;
use App\Models\ProviderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceTypeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_access_service_types_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('service-types.index'))
            ->assertStatus(200)
            ->assertViewIs('service-types.index')
            ->assertViewHas('serviceTypes');
    }

    /** @test */
    public function admin_can_filter_service_types_by_search()
    {
        ServiceType::factory()->create(['name' => 'Service Alpha']);
        ServiceType::factory()->create(['name' => 'Service Beta']);

        $this->actingAs($this->adminUser)
            ->get(route('service-types.index', ['search' => 'Alpha']))
            ->assertStatus(200)
            ->assertSee('Service Alpha')
            ->assertDontSee('Service Beta');
    }

    /** @test */
    public function admin_can_filter_service_types_by_status()
    {
        ServiceType::factory()->create(['status' => true, 'name' => 'Active Service']);
        ServiceType::factory()->create(['status' => false, 'name' => 'Inactive Service']);

        $this->actingAs($this->adminUser)
            ->get(route('service-types.index', ['status' => '1']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_service_type_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('service-types.create'))
            ->assertStatus(200)
            ->assertViewIs('service-types.create');
    }

    /** @test */
    public function admin_can_store_service_type()
    {
        $data = [
            'name' => 'Service Teste',
            'description' => 'Descrição detalhada do serviço teste',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('service-types.store'), $data)
            ->assertRedirect(route('service-types.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_types', [
            'name' => 'Service Teste',
            'description' => 'Descrição detalhada do serviço teste',
            'status' => true
        ]);
    }

    /** @test */
    public function validation_fails_when_required_service_type_fields_are_missing()
    {
        $data = [
            'name' => '',
            'description' => '',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('service-types.store'), $data)
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function admin_can_view_service_type()
    {
        $serviceType = ServiceType::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('service-types.show', $serviceType))
            ->assertStatus(200)
            ->assertViewIs('service-types.show')
            ->assertViewHas('serviceType');
    }

    /** @test */
    public function admin_can_edit_service_type()
    {
        $serviceType = ServiceType::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('service-types.edit', $serviceType))
            ->assertStatus(200)
            ->assertViewIs('service-types.edit')
            ->assertViewHas('serviceType');
    }

    /** @test */
    public function admin_can_update_service_type()
    {
        $serviceType = ServiceType::factory()->create([
            'name' => 'Original Service',
            'description' => 'Original description'
        ]);

        $data = [
            'name' => 'Updated Service',
            'description' => 'Updated description',
            'status' => false
        ];

        $this->actingAs($this->adminUser)
            ->put(route('service-types.update', $serviceType), $data)
            ->assertRedirect(route('service-types.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('service_types', [
            'id' => $serviceType->id,
            'name' => 'Updated Service',
            'description' => 'Updated description',
            'status' => false
        ]);
    }

    /** @test */
    public function admin_can_delete_service_type()
    {
        $serviceType = ServiceType::factory()->create();

        $this->actingAs($this->adminUser)
            ->delete(route('service-types.destroy', $serviceType))
            ->assertRedirect(route('service-types.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('service_types', [
            'id' => $serviceType->id
        ]);
    }

    /** @test */
    public function cannot_delete_service_type_with_associated_budgets()
    {
        $serviceType = ServiceType::factory()->create();

        // Create a budget that uses this service type
        Budget::factory()->create([
            'service_type_id' => $serviceType->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('service-types.destroy', $serviceType))
            ->assertRedirect(route('service-types.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('service_types', [
            'id' => $serviceType->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function cannot_delete_service_type_with_associated_provider_services()
    {
        $serviceType = ServiceType::factory()->create();

        // Create a provider service that uses this service type
        ProviderService::factory()->create([
            'service_type_id' => $serviceType->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('service-types.destroy', $serviceType))
            ->assertRedirect(route('service-types.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('service_types', [
            'id' => $serviceType->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function cannot_create_service_type_with_duplicate_name()
    {
        ServiceType::factory()->create([
            'name' => 'Existing Service'
        ]);

        $data = [
            'name' => 'Existing Service',
            'description' => 'Different description',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('service-types.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function service_type_show_displays_related_budgets_count()
    {
        $serviceType = ServiceType::factory()->create();

        // Create some budgets for this service type
        Budget::factory()->count(3)->create([
            'service_type_id' => $serviceType->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('service-types.show', $serviceType));

        $response->assertStatus(200)
            ->assertSee('3'); // Should show budget count
    }

    /** @test */
    public function service_type_show_displays_related_provider_services_count()
    {
        $serviceType = ServiceType::factory()->create();

        // Create some provider services for this service type
        ProviderService::factory()->count(2)->create([
            'service_type_id' => $serviceType->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('service-types.show', $serviceType));

        $response->assertStatus(200)
            ->assertSee('2'); // Should show provider services count
    }

    /** @test */
    public function status_toggle_works_correctly()
    {
        $serviceType = ServiceType::factory()->create(['status' => true]);

        $data = [
            'name' => $serviceType->name,
            'description' => $serviceType->description,
            'status' => false
        ];

        $this->actingAs($this->adminUser)
            ->put(route('service-types.update', $serviceType), $data)
            ->assertRedirect(route('service-types.index'));

        $this->assertDatabaseHas('service_types', [
            'id' => $serviceType->id,
            'status' => false
        ]);
    }
}
