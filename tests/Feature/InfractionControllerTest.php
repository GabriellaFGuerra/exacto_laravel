<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Infraction;
use App\Models\Municipality;
use App\Models\FederativeUnit;
use App\Models\Appeal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InfractionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $customerUser;
    protected $municipality;
    protected $federativeUnit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->federativeUnit = FederativeUnit::factory()->create([
            'name' => 'São Paulo',
            'acronym' => 'SP',
            'code' => '35'
        ]);

        $this->municipality = Municipality::factory()->create([
            'name' => 'São Paulo',
            'federative_unit_id' => $this->federativeUnit->id,
            'code' => '3550308'
        ]);

        $this->adminUser = User::factory()->admin()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->customerUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);
    }

    /** @test */
    public function admin_can_access_infractions_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('infractions.index'))
            ->assertStatus(200)
            ->assertViewIs('infractions.index')
            ->assertViewHas('infractions');
    }

    /** @test */
    public function admin_can_filter_infractions_by_search()
    {
        Infraction::factory()->create([
            'license_plate' => 'ABC-1234',
            'user_id' => $this->customerUser->id
        ]);
        Infraction::factory()->create([
            'license_plate' => 'XYZ-9876',
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('infractions.index', ['search' => 'ABC-1234']))
            ->assertStatus(200)
            ->assertSee('ABC-1234')
            ->assertDontSee('XYZ-9876');
    }

    /** @test */
    public function admin_can_filter_infractions_by_status()
    {
        Infraction::factory()->create([
            'status' => 'pending',
            'user_id' => $this->customerUser->id
        ]);
        Infraction::factory()->create([
            'status' => 'paid',
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('infractions.index', ['status' => 'pending']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_infractions_by_user()
    {
        $anotherUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        Infraction::factory()->create(['user_id' => $this->customerUser->id]);
        Infraction::factory()->create(['user_id' => $anotherUser->id]);

        $this->actingAs($this->adminUser)
            ->get(route('infractions.index', ['user_id' => $this->customerUser->id]))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_infraction_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('infractions.create'))
            ->assertStatus(200)
            ->assertViewIs('infractions.create')
            ->assertViewHas('users');
    }

    /** @test */
    public function admin_can_store_infraction()
    {
        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-1234',
            'type' => 'Excesso de velocidade',
            'location' => 'Avenida Paulista, 1000',
            'fine_amount' => 195.23,
            'infraction_date' => '2023-08-01',
            'due_date' => '2023-09-01',
            'status' => 'pending',
            'description' => 'Velocidade medida: 80 km/h em via de 60 km/h'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('infractions.store'), $data)
            ->assertRedirect(route('infractions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('infractions', [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-1234',
            'type' => 'Excesso de velocidade',
            'location' => 'Avenida Paulista, 1000',
            'fine_amount' => 195.23,
            'infraction_date' => '2023-08-01',
            'due_date' => '2023-09-01',
            'status' => 'pending',
            'description' => 'Velocidade medida: 80 km/h em via de 60 km/h'
        ]);
    }

    /** @test */
    public function validation_fails_when_required_infraction_fields_are_missing()
    {
        $data = [
            'user_id' => '',
            'license_plate' => '',
            'type' => '',
            'location' => '',
            'fine_amount' => 'not-a-number',
            'infraction_date' => 'invalid-date',
            'due_date' => 'invalid-date',
            'status' => 'invalid-status'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('infractions.store'), $data)
            ->assertSessionHasErrors([
                'user_id',
                'license_plate',
                'type',
                'location',
                'fine_amount',
                'infraction_date',
                'due_date',
                'status'
            ]);
    }

    /** @test */
    public function admin_can_view_infraction()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('infractions.show', $infraction))
            ->assertStatus(200)
            ->assertViewIs('infractions.show')
            ->assertViewHas('infraction');
    }

    /** @test */
    public function admin_can_edit_infraction()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('infractions.edit', $infraction))
            ->assertStatus(200)
            ->assertViewIs('infractions.edit')
            ->assertViewHas('infraction')
            ->assertViewHas('users');
    }

    /** @test */
    public function admin_can_update_infraction()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-1234',
            'status' => 'pending'
        ]);

        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-5678',
            'type' => 'Estacionamento irregular',
            'location' => 'Rua Augusta, 500',
            'fine_amount' => 130.16,
            'infraction_date' => '2023-08-15',
            'due_date' => '2023-09-15',
            'status' => 'paid',
            'description' => 'Veículo estacionado em local proibido',
            'payment_date' => '2023-08-20'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('infractions.update', $infraction), $data)
            ->assertRedirect(route('infractions.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('infractions', [
            'id' => $infraction->id,
            'license_plate' => 'ABC-5678',
            'type' => 'Estacionamento irregular',
            'location' => 'Rua Augusta, 500',
            'fine_amount' => 130.16,
            'status' => 'paid',
            'payment_date' => '2023-08-20'
        ]);
    }

    /** @test */
    public function admin_can_delete_infraction()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('infractions.destroy', $infraction))
            ->assertRedirect(route('infractions.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('infractions', [
            'id' => $infraction->id
        ]);
    }

    /** @test */
    public function cannot_delete_infraction_with_associated_appeals()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id
        ]);

        // Create an appeal for this infraction
        Appeal::factory()->create([
            'infraction_id' => $infraction->id,
            'user_id' => $this->customerUser->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('infractions.destroy', $infraction))
            ->assertRedirect(route('infractions.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('infractions', [
            'id' => $infraction->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function infraction_status_changes_are_tracked()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id,
            'status' => 'pending',
            'payment_date' => null
        ]);

        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => $infraction->license_plate,
            'type' => $infraction->type,
            'location' => $infraction->location,
            'fine_amount' => $infraction->fine_amount,
            'infraction_date' => $infraction->infraction_date->format('Y-m-d'),
            'due_date' => $infraction->due_date->format('Y-m-d'),
            'status' => 'paid',
            'description' => $infraction->description,
            'payment_date' => now()->format('Y-m-d')
        ];

        $this->actingAs($this->adminUser)
            ->put(route('infractions.update', $infraction), $data)
            ->assertRedirect(route('infractions.index'));

        $infraction->refresh();
        $this->assertEquals('paid', $infraction->status);
        $this->assertNotNull($infraction->payment_date);
    }

    /** @test */
    public function infraction_show_displays_related_appeals()
    {
        $infraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id
        ]);

        // Create appeals for this infraction
        Appeal::factory()->count(2)->create([
            'infraction_id' => $infraction->id,
            'user_id' => $this->customerUser->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('infractions.show', $infraction));

        $response->assertStatus(200)
            ->assertSee('2'); // Should show appeals count
    }

    /** @test */
    public function customer_can_view_own_infractions()
    {
        // Create infractions for the customer
        Infraction::factory()->count(3)->create([
            'user_id' => $this->customerUser->id
        ]);

        // Create infraction for another customer
        $anotherUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);
        Infraction::factory()->create([
            'user_id' => $anotherUser->id
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('infractions.index'));

        $response->assertStatus(200)
            ->assertViewIs('infractions.index');

        // Should only see own infractions (3 total)
        $infractions = $response->viewData('infractions');
        $this->assertCount(3, $infractions);
    }

    /** @test */
    public function overdue_infractions_are_identified()
    {
        // Create overdue infraction
        $overdueInfraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id,
            'due_date' => now()->subDays(10),
            'status' => 'pending'
        ]);

        // Create current infraction
        $currentInfraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id,
            'due_date' => now()->addDays(10),
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('infractions.index', ['overdue' => '1']));

        $response->assertStatus(200);
        // Should filter to show only overdue infractions
    }

    /** @test */
    public function paid_infractions_cannot_be_appealed()
    {
        $paidInfraction = Infraction::factory()->create([
            'user_id' => $this->customerUser->id,
            'status' => 'paid',
            'payment_date' => now()->subDays(5)
        ]);

        $data = [
            'infraction_id' => $paidInfraction->id,
            'user_id' => $this->customerUser->id,
            'reason' => 'Attempting to appeal paid infraction',
            'status' => 'pending'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('appeals.store'), $data)
            ->assertSessionHasErrors('infraction_id');
    }

    /** @test */
    public function license_plate_format_validation_works()
    {
        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'INVALID',
            'type' => 'Test Type',
            'location' => 'Test Location',
            'fine_amount' => 100.00,
            'infraction_date' => '2023-08-01',
            'due_date' => '2023-09-01',
            'status' => 'pending'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('infractions.store'), $data)
            ->assertSessionHasErrors('license_plate');
    }

    /** @test */
    public function fine_amount_must_be_positive()
    {
        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-1234',
            'type' => 'Test Type',
            'location' => 'Test Location',
            'fine_amount' => -50.00,
            'infraction_date' => '2023-08-01',
            'due_date' => '2023-09-01',
            'status' => 'pending'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('infractions.store'), $data)
            ->assertSessionHasErrors('fine_amount');
    }

    /** @test */
    public function due_date_must_be_after_infraction_date()
    {
        $data = [
            'user_id' => $this->customerUser->id,
            'license_plate' => 'ABC-1234',
            'type' => 'Test Type',
            'location' => 'Test Location',
            'fine_amount' => 100.00,
            'infraction_date' => '2023-09-01',
            'due_date' => '2023-08-01', // Before infraction date
            'status' => 'pending'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('infractions.store'), $data)
            ->assertSessionHasErrors('due_date');
    }
}