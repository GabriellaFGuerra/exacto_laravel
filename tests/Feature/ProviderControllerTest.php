<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Provider;
use App\Models\Municipality;
use App\Models\FederativeUnit;
use App\Models\ServiceType;
use App\Models\ProviderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $municipality;
    protected $federativeUnit;
    protected $serviceType;

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

        $this->serviceType = ServiceType::factory()->create();
    }

    /** @test */
    public function admin_can_access_providers_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('providers.index'))
            ->assertStatus(200)
            ->assertViewIs('providers.index')
            ->assertViewHas('providers');
    }

    /** @test */
    public function admin_can_filter_providers_by_search()
    {
        Provider::factory()->create(['name' => 'Provider Alpha']);
        Provider::factory()->create(['name' => 'Provider Beta']);

        $this->actingAs($this->adminUser)
            ->get(route('providers.index', ['search' => 'Alpha']))
            ->assertStatus(200)
            ->assertSee('Provider Alpha')
            ->assertDontSee('Provider Beta');
    }

    /** @test */
    public function admin_can_filter_providers_by_status()
    {
        Provider::factory()->create(['status' => true]);
        Provider::factory()->create(['status' => false]);

        $this->actingAs($this->adminUser)
            ->get(route('providers.index', ['status' => '1']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_provider_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('providers.create'))
            ->assertStatus(200)
            ->assertViewIs('providers.create')
            ->assertViewHas('municipalities')
            ->assertViewHas('serviceTypes');
    }

    /** @test */
    public function admin_can_store_provider()
    {
        $data = [
            'name' => 'Provider Teste',
            'cnpj' => '12345678901234',
            'email' => 'provider@teste.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality_id' => $this->municipality->id,
            'complement' => 'Sala 456',
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
            'status' => true,
            'services' => [$this->serviceType->id => '100.00']
        ];

        $this->actingAs($this->adminUser)
            ->post(route('providers.store'), $data)
            ->assertRedirect(route('providers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('providers', [
            'name' => 'Provider Teste',
            'cnpj' => '12345678901234',
            'email' => 'provider@teste.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality_id' => $this->municipality->id,
            'complement' => 'Sala 456',
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
            'status' => true,
        ]);

        // Check if provider service was created
        $provider = Provider::where('email', 'provider@teste.com')->first();
        $this->assertDatabaseHas('provider_services', [
            'provider_id' => $provider->id,
            'service_type_id' => $this->serviceType->id,
            'price' => '100.00'
        ]);
    }

    /** @test */
    public function validation_fails_when_required_provider_fields_are_missing()
    {
        $data = [
            'name' => '',
            'cnpj' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'address' => '',
            'number' => '',
            'municipality_id' => 999999,
            'neighborhood' => '',
            'zip_code' => ''
        ];

        $this->actingAs($this->adminUser)
            ->post(route('providers.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'cnpj',
                'email',
                'phone',
                'address',
                'number',
                'municipality_id',
                'neighborhood',
                'zip_code'
            ]);
    }

    /** @test */
    public function admin_can_view_provider()
    {
        $provider = Provider::factory()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('providers.show', $provider))
            ->assertStatus(200)
            ->assertViewIs('providers.show')
            ->assertViewHas('provider');
    }

    /** @test */
    public function admin_can_edit_provider()
    {
        $provider = Provider::factory()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('providers.edit', $provider))
            ->assertStatus(200)
            ->assertViewIs('providers.edit')
            ->assertViewHas('provider')
            ->assertViewHas('municipalities')
            ->assertViewHas('serviceTypes');
    }

    /** @test */
    public function admin_can_update_provider()
    {
        $provider = Provider::factory()->create([
            'municipality_id' => $this->municipality->id,
            'cnpj' => '12345678901234'
        ]);

        $data = [
            'name' => 'Provider Atualizado',
            'cnpj' => $provider->cnpj,
            'email' => 'atualizado@teste.com',
            'phone' => '(11) 12345-6789',
            'address' => 'Rua Atualizada, 456',
            'number' => '456',
            'municipality_id' => $this->municipality->id,
            'complement' => 'Andar 2',
            'neighborhood' => 'Bairro Atualizado',
            'zip_code' => '98765-432',
            'status' => false,
            'services' => [$this->serviceType->id => '200.00']
        ];

        $this->actingAs($this->adminUser)
            ->put(route('providers.update', $provider), $data)
            ->assertRedirect(route('providers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('providers', [
            'id' => $provider->id,
            'name' => 'Provider Atualizado',
            'email' => 'atualizado@teste.com',
            'phone' => '(11) 12345-6789',
            'address' => 'Rua Atualizada, 456',
            'number' => '456',
            'complement' => 'Andar 2',
            'neighborhood' => 'Bairro Atualizado',
            'zip_code' => '98765-432',
            'status' => false,
        ]);
    }

    /** @test */
    public function admin_can_delete_provider()
    {
        $provider = Provider::factory()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('providers.destroy', $provider))
            ->assertRedirect(route('providers.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('providers', [
            'id' => $provider->id
        ]);
    }

    /** @test */
    public function cannot_create_provider_with_duplicate_email()
    {
        Provider::factory()->create([
            'email' => 'existente@teste.com',
            'municipality_id' => $this->municipality->id
        ]);

        $data = [
            'name' => 'Novo Provider',
            'cnpj' => '98765432109876',
            'email' => 'existente@teste.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality_id' => $this->municipality->id,
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('providers.store'), $data)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function cannot_create_provider_with_duplicate_cnpj()
    {
        Provider::factory()->create([
            'cnpj' => '98765432109876',
            'municipality_id' => $this->municipality->id
        ]);

        $data = [
            'name' => 'Novo Provider',
            'cnpj' => '98765432109876',
            'email' => 'novo@teste.com',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality_id' => $this->municipality->id,
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('providers.store'), $data)
            ->assertSessionHasErrors('cnpj');
    }

    /** @test */
    public function provider_services_are_managed_correctly()
    {
        $provider = Provider::factory()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $serviceType2 = ServiceType::factory()->create();

        $data = [
            'name' => $provider->name,
            'cnpj' => $provider->cnpj,
            'email' => $provider->email,
            'phone' => $provider->phone,
            'address' => $provider->address,
            'number' => $provider->number,
            'municipality_id' => $provider->municipality_id,
            'neighborhood' => $provider->neighborhood,
            'zip_code' => $provider->zip_code,
            'status' => $provider->status,
            'services' => [
                $this->serviceType->id => '150.00',
                $serviceType2->id => '250.00'
            ]
        ];

        $this->actingAs($this->adminUser)
            ->put(route('providers.update', $provider), $data)
            ->assertRedirect(route('providers.index'));

        $this->assertDatabaseHas('provider_services', [
            'provider_id' => $provider->id,
            'service_type_id' => $this->serviceType->id,
            'price' => '150.00'
        ]);

        $this->assertDatabaseHas('provider_services', [
            'provider_id' => $provider->id,
            'service_type_id' => $serviceType2->id,
            'price' => '250.00'
        ]);
    }
}
