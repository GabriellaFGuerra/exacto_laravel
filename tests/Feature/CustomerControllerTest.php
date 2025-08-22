<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Municipality;
use App\Models\FederativeUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $municipality;
    protected $federativeUnit;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create federative unit
        $this->federativeUnit = FederativeUnit::factory()->create([
            'name' => 'São Paulo',
            'acronym' => 'SP',
            'code' => '35'
        ]);

        // Create municipality
        $this->municipality = Municipality::factory()->create([
            'name' => 'São Paulo',
            'federative_unit_id' => $this->federativeUnit->id,
            'code' => '3550308'
        ]);

        // Create admin user
        $this->adminUser = User::factory()->admin()->create([
            'municipality_id' => $this->municipality->id
        ]);
    }

    /** @test */
    public function admin_can_access_customers_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('customers.index'))
            ->assertStatus(200)
            ->assertViewIs('customers.index')
            ->assertViewHas('customers');
    }

    /** @test */
    public function admin_can_access_customer_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('customers.create'))
            ->assertStatus(200)
            ->assertViewIs('customers.create');
    }

    /** @test */
    public function admin_can_store_customer()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'name' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'cnpj' => '12345678901234',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality' => $this->municipality->id, // Note: controller uses 'municipality', not 'municipality_id'
            'complement' => 'Sala 456',
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
            'photo' => $file,
        ];

        $this->actingAs($this->adminUser)
            ->post(route('customers.store'), $data)
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Cliente Teste',
            'email' => 'cliente@teste.com',
            'user_type' => 'customer',
            'cnpj' => '12345678901234',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality_id' => $this->municipality->id,
            'complement' => 'Sala 456',
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
        ]);

        // Check if file was stored
        $user = User::where('email', 'cliente@teste.com')->first();
        $this->assertNotNull($user->photo);
        $this->assertTrue(Storage::disk('public')->exists($user->photo));
    }

    /** @test */
    public function validation_fails_when_required_customer_fields_are_missing()
    {
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '456',
            'cnpj' => '',
            'phone' => '',
            'address' => '',
            'number' => '',
            'municipality' => 999999, // Note: controller uses 'municipality', not 'municipality_id'
            'neighborhood' => '',
            'zip_code' => ''
        ];

        $this->actingAs($this->adminUser)
            ->post(route('customers.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'email',
                'password',
                'cnpj',
                'phone',
                'address',
                'number',
                'municipality',
                'neighborhood',
                'zip_code'
            ]);
    }

    /** @test */
    public function admin_can_view_customer()
    {
        $customer = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('customers.show', $customer->id))
            ->assertStatus(200)
            ->assertViewIs('customers.show')
            ->assertViewHas('customer');
    }

    /** @test */
    public function admin_can_edit_customer()
    {
        $customer = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('customers.edit', $customer->id))
            ->assertStatus(200)
            ->assertViewIs('customers.edit')
            ->assertViewHas('customer');
    }

    /** @test */
    public function admin_can_update_customer()
    {
        $customer = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id,
            'cnpj' => '12345678901234'
        ]);

        $file = UploadedFile::fake()->image('new_avatar.jpg');

        $data = [
            'name' => 'Cliente Atualizado',
            'email' => 'atualizado@teste.com',
            // Senha não obrigatória no update
            'cnpj' => $customer->cnpj,
            'phone' => '(11) 12345-6789',
            'address' => 'Rua Atualizada, 456',
            'number' => '456',
            'municipality' => $this->municipality->id, // Note: controller uses 'municipality', not 'municipality_id'
            'complement' => 'Andar 2',
            'neighborhood' => 'Bairro Atualizado',
            'zip_code' => '98765-432',
            'photo' => $file,
        ];

        $this->actingAs($this->adminUser)
            ->put(route('customers.update', $customer->id), $data)
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'name' => 'Cliente Atualizado',
            'email' => 'atualizado@teste.com',
            'phone' => '(11) 12345-6789',
            'address' => 'Rua Atualizada, 456',
            'number' => '456',
            'complement' => 'Andar 2',
            'neighborhood' => 'Bairro Atualizado',
            'zip_code' => '98765-432',
        ]);

        // Check if new file was stored
        $customer->refresh();
        $this->assertNotNull($customer->photo);
        $this->assertTrue(Storage::disk('public')->exists($customer->photo));
    }

    /** @test */
    public function admin_can_delete_customer()
    {
        $customer = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('customers.destroy', $customer->id))
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        // Since User model uses SoftDeletes, check soft deletion
        $this->assertSoftDeleted('users', [
            'id' => $customer->id
        ]);
    }

    /** @test */
    public function cannot_create_customer_with_duplicate_email()
    {
        User::factory()->customer()->create([
            'email' => 'existente@teste.com',
            'municipality_id' => $this->municipality->id
        ]);

        $data = [
            'name' => 'Novo Cliente',
            'email' => 'existente@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'cnpj' => '98765432109876',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality' => $this->municipality->id,
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('customers.store'), $data)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function cannot_create_customer_with_duplicate_cnpj()
    {
        User::factory()->customer()->create([
            'cnpj' => '98765432109876',
            'municipality_id' => $this->municipality->id
        ]);

        $data = [
            'name' => 'Novo Cliente',
            'email' => 'novo@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'cnpj' => '98765432109876',
            'phone' => '(11) 98765-4321',
            'address' => 'Rua Teste, 123',
            'number' => '123',
            'municipality' => $this->municipality->id,
            'neighborhood' => 'Bairro Teste',
            'zip_code' => '12345-678',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('customers.store'), $data)
            ->assertSessionHasErrors('cnpj');
    }
}