<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\User;
use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\Manager;
use App\Models\Municipality;
use App\Models\BudgetProvider;
use App\Models\FederativeUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BudgetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $customerUser;
    protected $serviceType;
    protected $manager;
    protected $provider;
    protected $budget;
    protected $municipality;
    protected $federativeUnit;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar armazenamento fake
        Storage::fake('public');

        // Criar federative unit para testes
        $this->federativeUnit = FederativeUnit::factory()->create([
            'name' => 'São Paulo',
            'acronym' => 'SP',
            'code' => '35'
        ]);

        // Criar município para testes
        $this->municipality = Municipality::factory()->create([
            'name' => 'São Paulo',
            'federative_unit_id' => $this->federativeUnit->id,
            'code' => '3550308'
        ]);

        // Criar usuários para teste
        $this->adminUser = User::factory()->admin()->create([
            'municipality_id' => $this->municipality->id
        ]);

        $this->customerUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        // Criar um tipo de serviço
        $this->serviceType = ServiceType::factory()->create([
            'name' => 'Consultoria',
            'status' => 1
        ]);

        // Criar um gestor
        $this->manager = Manager::factory()->create([
            'name' => 'Gestor Teste'
        ]);

        // Criar um fornecedor
        $this->provider = Provider::factory()->create([
            'name' => 'Fornecedor Teste',
            'email' => 'fornecedor@teste.com',
            'cnpj' => '12.345.678/0001-90',
            'municipality_id' => $this->municipality->id,
        ]);

        // Criar um orçamento
        $this->budget = Budget::factory()->create([
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'custom_service_type' => null,
            'progress' => 0,
            'observation' => 'Observação teste',
            'approval_date' => null,
            'responsible_user_id' => $this->adminUser->id,
            'responsible_manager_id' => $this->manager->id,
            'deadline' => '2025-12-31',
            'status' => 'open',
            'spreadsheet' => null
        ]);
    }

    /** @test */
    public function admin_can_access_budget_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('budgets.index'))
            ->assertStatus(200)
            ->assertViewIs('budgets.index')
            ->assertViewHas('budgets');
    }

    /** @test */
    public function admin_can_access_budget_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('budgets.create'))
            ->assertStatus(200)
            ->assertViewIs('budgets.create')
            ->assertViewHas(['customers', 'serviceTypes', 'responsibleUsers', 'managers', 'providers']);
    }

    /** @test */
    public function admin_can_store_budget()
    {
        $file = UploadedFile::fake()->create('planilha.xlsx', 500);

        $data = [
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'progress' => 10,
            'observation' => 'Nova observação',
            'responsible_user_id' => $this->adminUser->id,
            'responsible_manager_id' => $this->manager->id,
            'deadline' => '2025-10-15',
            'status' => 'open',
            'spreadsheets' => [$file],
            'providers' => [
                [
                    'provider_id' => $this->provider->id,
                    'value' => 1500.50,
                    'observation' => 'Observação do fornecedor'
                ]
            ]
        ];

        $this->actingAs($this->adminUser)
            ->post(route('budgets.store'), $data)
            ->assertRedirect(route('budgets.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budgets', [
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'progress' => 10,
            'observation' => 'Nova observação',
            'responsible_user_id' => $this->adminUser->id,
            'responsible_manager_id' => $this->manager->id,
            'status' => 'open',
        ]);

        $latestBudget = Budget::latest('id')->first();
        $this->assertDatabaseHas('budget_providers', [
            'budget_id' => $latestBudget->id,
            'provider_id' => $this->provider->id,
            'value' => 1500.50,
            'observation' => 'Observação do fornecedor'
        ]);
    }

    /** @test */
    public function validation_fails_when_required_budget_fields_are_missing()
    {
        $data = [
            'customer_id' => '',
            'service_type_id' => '',
            'progress' => 'não é número',
            'responsible_user_id' => '',
            'status' => 'status_inválido'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('budgets.store'), $data)
            ->assertSessionHasErrors(['customer_id', 'service_type_id', 'progress', 'responsible_user_id', 'status']);
    }

    /** @test */
    public function admin_can_view_budget()
    {
        $this->actingAs($this->adminUser)
            ->get(route('budgets.show', $this->budget->id))
            ->assertStatus(200)
            ->assertViewIs('budgets.show')
            ->assertViewHas('budget');
    }

    /** @test */
    public function admin_can_edit_budget()
    {
        $this->actingAs($this->adminUser)
            ->get(route('budgets.edit', $this->budget->id))
            ->assertStatus(200)
            ->assertViewIs('budgets.edit')
            ->assertViewHas(['budget', 'customers', 'serviceTypes', 'responsibleUsers', 'managers', 'providers']);
    }

    /** @test */
    public function admin_can_update_budget()
    {
        $file = UploadedFile::fake()->create('nova_planilha.xlsx', 500);

        $data = [
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'progress' => 50,
            'observation' => 'Observação atualizada',
            'responsible_user_id' => $this->adminUser->id,
            'responsible_manager_id' => $this->manager->id,
            'deadline' => '2025-11-30',
            'status' => 'pending',
            'spreadsheets' => [$file],
            'providers' => [
                [
                    'provider_id' => $this->provider->id,
                    'value' => 2000.00,
                    'observation' => 'Observação atualizada'
                ]
            ]
        ];

        $this->actingAs($this->adminUser)
            ->put(route('budgets.update', $this->budget->id), $data)
            ->assertRedirect(route('budgets.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budgets', [
            'id' => $this->budget->id,
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'progress' => 50,
            'observation' => 'Observação atualizada',
            'responsible_user_id' => $this->adminUser->id,
            'responsible_manager_id' => $this->manager->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function admin_can_delete_budget()
    {
        $this->actingAs($this->adminUser)
            ->delete(route('budgets.destroy', $this->budget->id))
            ->assertRedirect(route('budgets.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('budgets', [
            'id' => $this->budget->id
        ]);
    }

    /** @test */
    public function admin_can_add_provider_to_budget()
    {
        $data = [
            'provider_id' => $this->provider->id,
            'value' => 3500.75,
            'observation' => 'Novo fornecedor adicionado'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('budgets.providers.add', $this->budget->id), $data)
            ->assertRedirect(route('budgets.show', $this->budget->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budget_providers', [
            'budget_id' => $this->budget->id,
            'provider_id' => $this->provider->id,
            'value' => 3500.75,
            'observation' => 'Novo fornecedor adicionado'
        ]);
    }

    /** @test */
    public function admin_can_update_provider_in_budget()
    {
        // Primeiro adicionar um fornecedor para poder atualizar
        $budgetProvider = BudgetProvider::create([
            'budget_id' => $this->budget->id,
            'provider_id' => $this->provider->id,
            'value' => 1000.00,
            'observation' => 'Observação inicial'
        ]);

        $data = [
            'value' => 1250.00,
            'observation' => 'Observação modificada'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('budgets.providers.update', [$this->budget->id, $budgetProvider->id]), $data)
            ->assertRedirect(route('budgets.show', $this->budget->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('budget_providers', [
            'id' => $budgetProvider->id,
            'budget_id' => $this->budget->id,
            'provider_id' => $this->provider->id,
            'value' => 1250.00,
            'observation' => 'Observação modificada'
        ]);
    }

    /** @test */
    public function admin_can_remove_provider_from_budget()
    {
        // Primeiro adicionar um fornecedor para poder remover
        $budgetProvider = BudgetProvider::create([
            'budget_id' => $this->budget->id,
            'provider_id' => $this->provider->id,
            'value' => 1000.00,
            'observation' => 'Observação inicial'
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('budgets.providers.remove', [$this->budget->id, $budgetProvider->id]))
            ->assertRedirect(route('budgets.show', $this->budget->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('budget_providers', [
            'id' => $budgetProvider->id
        ]);
    }

    /** @test */
    public function customer_can_view_own_budgets()
    {
        $this->actingAs($this->customerUser)
            ->get(route('customer.budgets'))
            ->assertStatus(200)
            ->assertViewIs('budgets.customer')
            ->assertViewHas('budgets');
    }

    /** @test */
    public function admin_can_download_budget_file()
    {
        // Criar um orçamento com arquivo
        $file = UploadedFile::fake()->create('orcamento.xlsx', 500);
        $filePath = $file->store('budgets/2023/08/' . $this->customerUser->id . '/' . $this->budget->id, 'public');

        $budget = Budget::factory()->create([
            'customer_id' => $this->customerUser->id,
            'service_type_id' => $this->serviceType->id,
            'responsible_user_id' => $this->adminUser->id,
            'spreadsheets' => [$filePath],
            'status' => 'open'
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('budgets.download', [$budget->id, 0]))
            ->assertStatus(200);
    }
}