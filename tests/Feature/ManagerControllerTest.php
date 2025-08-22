<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Manager;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManagerControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_access_managers_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('managers.index'))
            ->assertStatus(200)
            ->assertViewIs('managers.index')
            ->assertViewHas('managers');
    }

    /** @test */
    public function admin_can_filter_managers_by_search()
    {
        Manager::factory()->create(['name' => 'Manager Alpha']);
        Manager::factory()->create(['name' => 'Manager Beta']);

        $this->actingAs($this->adminUser)
            ->get(route('managers.index', ['search' => 'Alpha']))
            ->assertStatus(200)
            ->assertSee('Manager Alpha')
            ->assertDontSee('Manager Beta');
    }

    /** @test */
    public function admin_can_filter_managers_by_department()
    {
        Manager::factory()->create(['department' => 'Finance']);
        Manager::factory()->create(['department' => 'Legal']);

        $this->actingAs($this->adminUser)
            ->get(route('managers.index', ['department' => 'Finance']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_managers_by_status()
    {
        Manager::factory()->create(['status' => true, 'name' => 'Active Manager']);
        Manager::factory()->create(['status' => false, 'name' => 'Inactive Manager']);

        $this->actingAs($this->adminUser)
            ->get(route('managers.index', ['status' => '1']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_manager_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('managers.create'))
            ->assertStatus(200)
            ->assertViewIs('managers.create');
    }

    /** @test */
    public function admin_can_store_manager()
    {
        $photo = UploadedFile::fake()->image('manager.jpg');

        $data = [
            'name' => 'Manager Teste',
            'email' => 'manager@teste.com',
            'phone' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'department' => 'Finance',
            'position' => 'Senior Manager',
            'hire_date' => '2023-01-15',
            'status' => true,
            'bio' => 'Experienced manager with 10+ years',
            'photo' => $photo
        ];

        $this->actingAs($this->adminUser)
            ->post(route('managers.store'), $data)
            ->assertRedirect(route('managers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('managers', [
            'name' => 'Manager Teste',
            'email' => 'manager@teste.com',
            'phone' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'department' => 'Finance',
            'position' => 'Senior Manager',
            'hire_date' => '2023-01-15',
            'status' => true,
            'bio' => 'Experienced manager with 10+ years'
        ]);

        // Check if photo was stored
        $manager = Manager::where('email', 'manager@teste.com')->first();
        $this->assertNotNull($manager->photo);
        $this->assertTrue(Storage::disk('public')->exists($manager->photo));
    }

    /** @test */
    public function validation_fails_when_required_manager_fields_are_missing()
    {
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'document' => '',
            'department' => '',
            'position' => ''
        ];

        $this->actingAs($this->adminUser)
            ->post(route('managers.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'email'
            ]);
    }

    /** @test */
    public function admin_can_view_manager()
    {
        $manager = Manager::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('managers.show', $manager))
            ->assertStatus(200)
            ->assertViewIs('managers.show')
            ->assertViewHas('manager');
    }

    /** @test */
    public function admin_can_edit_manager()
    {
        $manager = Manager::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('managers.edit', $manager))
            ->assertStatus(200)
            ->assertViewIs('managers.edit')
            ->assertViewHas('manager');
    }

    /** @test */
    public function admin_can_update_manager()
    {
        $manager = Manager::factory()->create([
            'name' => 'Original Manager',
            'email' => 'original@teste.com'
        ]);

        $newPhoto = UploadedFile::fake()->image('new_manager.jpg');

        $data = [
            'name' => 'Updated Manager',
            'email' => 'updated@teste.com',
            'phone' => '(11) 12345-6789',
            'document' => '987.654.321-09',
            'department' => 'Legal',
            'position' => 'Manager',
            'hire_date' => '2023-06-01',
            'status' => false,
            'bio' => 'Updated biography',
            'photo' => $newPhoto
        ];

        $this->actingAs($this->adminUser)
            ->put(route('managers.update', $manager), $data)
            ->assertRedirect(route('managers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('managers', [
            'id' => $manager->id,
            'name' => 'Updated Manager',
            'email' => 'updated@teste.com',
            'phone' => '(11) 12345-6789',
            'document' => '987.654.321-09',
            'department' => 'Legal',
            'position' => 'Manager',
            'hire_date' => '2023-06-01',
            'status' => false,
            'bio' => 'Updated biography'
        ]);

        // Check if new photo was stored
        $manager->refresh();
        $this->assertNotNull($manager->photo);
        $this->assertTrue(Storage::disk('public')->exists($manager->photo));
    }

    /** @test */
    public function admin_can_update_manager_and_remove_photo()
    {
        $photo = UploadedFile::fake()->image('original.jpg');
        $manager = Manager::factory()->create(['photo' => $photo->store('managers', 'public')]);

        $data = [
            'name' => $manager->name,
            'email' => $manager->email,
            'phone' => $manager->phone,
            'document' => $manager->document,
            'department' => $manager->department,
            'position' => $manager->position,
            'status' => $manager->status,
            'remove_photo' => '1'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('managers.update', $manager), $data)
            ->assertRedirect(route('managers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('managers', [
            'id' => $manager->id,
            'photo' => null
        ]);
    }

    /** @test */
    public function admin_can_delete_manager()
    {
        $manager = Manager::factory()->create();

        $this->actingAs($this->adminUser)
            ->delete(route('managers.destroy', $manager))
            ->assertRedirect(route('managers.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('managers', [
            'id' => $manager->id
        ]);
    }

    /** @test */
    public function cannot_create_manager_with_duplicate_email()
    {
        Manager::factory()->create([
            'email' => 'existente@teste.com'
        ]);

        $data = [
            'name' => 'Novo Manager',
            'email' => 'existente@teste.com',
            'phone' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'department' => 'Finance',
            'position' => 'Manager',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('managers.store'), $data)
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function cannot_create_manager_with_duplicate_document()
    {
        Manager::factory()->create([
            'document' => '123.456.789-01'
        ]);

        $data = [
            'name' => 'Novo Manager',
            'email' => 'novo@teste.com',
            'phone' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'department' => 'Finance',
            'position' => 'Manager',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('managers.store'), $data)
            ->assertSessionHasErrors('document');
    }

    /** @test */
    public function manager_show_displays_budget_statistics()
    {
        $manager = Manager::factory()->create();

        // Create budgets for this manager
        Budget::factory()->count(3)->create([
            'manager_id' => $manager->id,
            'status' => 'approved',
            'total_amount' => 1000.00
        ]);

        Budget::factory()->count(2)->create([
            'manager_id' => $manager->id,
            'status' => 'pending',
            'total_amount' => 500.00
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('managers.show', $manager));

        $response->assertStatus(200)
            ->assertSee('5') // Total budgets
            ->assertSee('3') // Approved budgets
            ->assertSee('2') // Pending budgets
            ->assertSee('3.500,00'); // Total amount formatted
    }

    /** @test */
    public function photo_upload_validation_works()
    {
        $invalidFile = UploadedFile::fake()->create('document.pdf', 100);

        $data = [
            'name' => 'Manager Teste',
            'email' => 'manager@teste.com',
            'phone' => '(11) 98765-4321',
            'document' => '123.456.789-01',
            'department' => 'Finance',
            'position' => 'Manager',
            'status' => true,
            'photo' => $invalidFile
        ];

        $this->actingAs($this->adminUser)
            ->post(route('managers.store'), $data)
            ->assertSessionHasErrors('photo');
    }

    /** @test */
    public function can_update_manager_without_changing_email()
    {
        $manager = Manager::factory()->create([
            'email' => 'unique@teste.com',
            'name' => 'Original Name'
        ]);

        $data = [
            'name' => 'Updated Name',
            'email' => 'unique@teste.com', // Same email
            'phone' => $manager->phone,
            'document' => $manager->document,
            'department' => $manager->department,
            'position' => $manager->position,
            'status' => $manager->status
        ];

        $this->actingAs($this->adminUser)
            ->put(route('managers.update', $manager), $data)
            ->assertRedirect(route('managers.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('managers', [
            'id' => $manager->id,
            'name' => 'Updated Name',
            'email' => 'unique@teste.com'
        ]);
    }
}
