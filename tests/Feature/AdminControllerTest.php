<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Municipality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $regularUser;
    protected $municipality;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar um município para testes
        $this->municipality = Municipality::factory()->create();
        
        // Criar um usuário administrador
        $this->adminUser = User::factory()->create([
            'user_type' => 'admin',
            'status' => true,
            'municipality_id' => $this->municipality->id
        ]);
        
        // Criar um usuário comum
        $this->regularUser = User::factory()->create([
            'user_type' => 'customer',
            'status' => true,
            'municipality_id' => $this->municipality->id
        ]);
    }

    /** @test */
    public function admin_can_access_admin_index_page()
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.index'))
            ->assertStatus(200)
            ->assertViewIs('admin.index')
            ->assertViewHas('users');
    }

    /** @test */
    public function non_admin_cannot_access_admin_index_page()
    {
        $this->actingAs($this->regularUser)
            ->get(route('admin.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_admin_create_page()
    {
        $this->actingAs($this->adminUser)
            ->get(route('admin.create'))
            ->assertStatus(200)
            ->assertViewIs('admin.create');
    }

    /** @test */
    public function admin_can_store_new_admin_user()
    {
        $data = [
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com',
            'login' => 'testadmin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'notification' => true,
            'address' => 'Test Address',
            'number' => '123',
            'complement' => 'Apt 456',
            'neighborhood' => 'Test Neighborhood',
            'municipality_id' => $this->municipality->id,
            'zip_code' => '12345-678',
            'phone' => '(11) 12345-6789',
            'cpf' => '123.456.789-00',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('admin.store'), $data)
            ->assertRedirect(route('admin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com',
            'login' => 'testadmin',
            'user_type' => 'admin',
            'notification' => true,
            'address' => 'Test Address',
            'number' => '123',
            'complement' => 'Apt 456',
            'neighborhood' => 'Test Neighborhood',
            'municipality_id' => $this->municipality->id,
            'zip_code' => '12345-678',
            'phone' => '(11) 12345-6789',
            'cpf' => '123.456.789-00',
        ]);
    }

    /** @test */
    public function validation_error_occurs_when_storing_admin_with_invalid_data()
    {
        $data = [
            'name' => '', // Nome vazio para forçar erro de validação
            'email' => 'invalid-email', // Email inválido
            'login' => '', // Login vazio
            'password' => '123', // Senha curta demais
            'password_confirmation' => '456', // Senha não confere
            'notification' => 'not-a-boolean', // Não é booleano
            'address' => '',
            'number' => '',
            'neighborhood' => '',
            'municipality_id' => 999999, // ID que não existe
            'zip_code' => '',
            'phone' => '',
            'cpf' => '',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('admin.store'), $data)
            ->assertSessionHasErrors([
                'name', 'email', 'login', 'password', 
                'notification', 'address', 'number', 'neighborhood',
                'municipality_id', 'zip_code', 'phone', 'cpf'
            ]);
    }

    /** @test */
    public function admin_can_view_admin_user_details()
    {
        $user = User::factory()->create([
            'user_type' => 'admin',
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('admin.show', $user->id))
            ->assertStatus(200)
            ->assertViewIs('admin.show')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function admin_can_edit_admin_user()
    {
        $user = User::factory()->create([
            'user_type' => 'admin',
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('admin.edit', $user->id))
            ->assertStatus(200)
            ->assertViewIs('admin.edit')
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function admin_can_update_admin_user()
    {
        $user = User::factory()->create([
            'user_type' => 'admin',
            'municipality_id' => $this->municipality->id,
            'cpf' => '111.222.333-44'
        ]);

        $data = [
            'name' => 'Updated Admin Name',
            'email' => $user->email,
            'login' => $user->login,
            'notification' => true,
            'address' => 'Updated Address',
            'number' => '456',
            'complement' => 'Updated Complement',
            'neighborhood' => 'Updated Neighborhood',
            'municipality_id' => $this->municipality->id,
            'zip_code' => '98765-432',
            'phone' => '(11) 98765-4321',
            'cpf' => $user->cpf,
            'status' => true,
        ];

        $this->actingAs($this->adminUser)
            ->put(route('admin.update', $user->id), $data)
            ->assertRedirect(route('admin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Admin Name',
            'address' => 'Updated Address',
            'number' => '456',
            'complement' => 'Updated Complement',
            'neighborhood' => 'Updated Neighborhood',
            'zip_code' => '98765-432',
            'phone' => '(11) 98765-4321',
        ]);
    }

    /** @test */
    public function admin_can_delete_admin_user()
    {
        $user = User::factory()->create([
            'user_type' => 'admin',
            'municipality_id' => $this->municipality->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('admin.destroy', $user->id))
            ->assertRedirect(route('admin.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}