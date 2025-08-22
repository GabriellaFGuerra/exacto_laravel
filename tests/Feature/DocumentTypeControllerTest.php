<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DocumentType;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DocumentTypeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->admin()->create();
    }

    /** @test */
    public function admin_can_access_document_types_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('document-types.index'))
            ->assertStatus(200)
            ->assertViewIs('document-types.index')
            ->assertViewHas('documentTypes');
    }

    /** @test */
    public function admin_can_filter_document_types_by_search()
    {
        DocumentType::factory()->create(['name' => 'RG']);
        DocumentType::factory()->create(['name' => 'CPF']);

        $this->actingAs($this->adminUser)
            ->get(route('document-types.index', ['search' => 'RG']))
            ->assertStatus(200)
            ->assertSee('RG')
            ->assertDontSee('CPF');
    }

    /** @test */
    public function admin_can_filter_document_types_by_status()
    {
        DocumentType::factory()->create(['status' => true, 'name' => 'Active Doc']);
        DocumentType::factory()->create(['status' => false, 'name' => 'Inactive Doc']);

        $this->actingAs($this->adminUser)
            ->get(route('document-types.index', ['status' => '1']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_document_type_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('document-types.create'))
            ->assertStatus(200)
            ->assertViewIs('document-types.create');
    }

    /** @test */
    public function admin_can_store_document_type()
    {
        $data = [
            'name' => 'CNH',
            'description' => 'Carteira Nacional de Habilitação',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('document-types.store'), $data)
            ->assertRedirect(route('document-types.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('document_types', [
            'name' => 'CNH',
            'description' => 'Carteira Nacional de Habilitação',
            'status' => true
        ]);
    }

    /** @test */
    public function validation_fails_when_required_document_type_fields_are_missing()
    {
        $data = [
            'name' => '',
            'description' => '',
        ];

        $this->actingAs($this->adminUser)
            ->post(route('document-types.store'), $data)
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function admin_can_view_document_type()
    {
        $documentType = DocumentType::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('document-types.show', $documentType))
            ->assertStatus(200)
            ->assertViewIs('document-types.show')
            ->assertViewHas('documentType');
    }

    /** @test */
    public function admin_can_edit_document_type()
    {
        $documentType = DocumentType::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('document-types.edit', $documentType))
            ->assertStatus(200)
            ->assertViewIs('document-types.edit')
            ->assertViewHas('documentType');
    }

    /** @test */
    public function admin_can_update_document_type()
    {
        $documentType = DocumentType::factory()->create([
            'name' => 'Original Type',
            'description' => 'Original description'
        ]);

        $data = [
            'name' => 'Updated Type',
            'description' => 'Updated description',
            'status' => false
        ];

        $this->actingAs($this->adminUser)
            ->put(route('document-types.update', $documentType), $data)
            ->assertRedirect(route('document-types.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('document_types', [
            'id' => $documentType->id,
            'name' => 'Updated Type',
            'description' => 'Updated description',
            'status' => false
        ]);
    }

    /** @test */
    public function admin_can_delete_document_type()
    {
        $documentType = DocumentType::factory()->create();

        $this->actingAs($this->adminUser)
            ->delete(route('document-types.destroy', $documentType))
            ->assertRedirect(route('document-types.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('document_types', [
            'id' => $documentType->id
        ]);
    }

    /** @test */
    public function cannot_delete_document_type_with_associated_documents()
    {
        $documentType = DocumentType::factory()->create();

        // Create a document that uses this document type
        Document::factory()->create([
            'document_type_id' => $documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('document-types.destroy', $documentType))
            ->assertRedirect(route('document-types.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('document_types', [
            'id' => $documentType->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function cannot_create_document_type_with_duplicate_name()
    {
        DocumentType::factory()->create([
            'name' => 'Existing Document Type'
        ]);

        $data = [
            'name' => 'Existing Document Type',
            'description' => 'Different description',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->post(route('document-types.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function document_type_show_displays_related_documents_count()
    {
        $documentType = DocumentType::factory()->create();

        // Create some documents for this document type
        Document::factory()->count(5)->create([
            'document_type_id' => $documentType->id
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('document-types.show', $documentType));

        $response->assertStatus(200)
            ->assertSee('5'); // Should show documents count
    }

    /** @test */
    public function document_type_show_displays_documents_by_status()
    {
        $documentType = DocumentType::factory()->create();

        // Create documents with different statuses
        Document::factory()->count(2)->create([
            'document_type_id' => $documentType->id,
            'status' => 'approved'
        ]);

        Document::factory()->count(3)->create([
            'document_type_id' => $documentType->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('document-types.show', $documentType));

        $response->assertStatus(200)
            ->assertSee('2') // Approved count
            ->assertSee('3'); // Pending count
    }

    /** @test */
    public function status_toggle_works_correctly()
    {
        $documentType = DocumentType::factory()->create(['status' => true]);

        $data = [
            'name' => $documentType->name,
            'description' => $documentType->description,
            'status' => false
        ];

        $this->actingAs($this->adminUser)
            ->put(route('document-types.update', $documentType), $data)
            ->assertRedirect(route('document-types.index'));

        $this->assertDatabaseHas('document_types', [
            'id' => $documentType->id,
            'status' => false
        ]);
    }

    /** @test */
    public function can_update_document_type_without_changing_name()
    {
        $documentType = DocumentType::factory()->create([
            'name' => 'Unique Type',
            'description' => 'Original description'
        ]);

        $data = [
            'name' => 'Unique Type', // Same name
            'description' => 'Updated description',
            'status' => true
        ];

        $this->actingAs($this->adminUser)
            ->put(route('document-types.update', $documentType), $data)
            ->assertRedirect(route('document-types.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('document_types', [
            'id' => $documentType->id,
            'name' => 'Unique Type',
            'description' => 'Updated description'
        ]);
    }
}
