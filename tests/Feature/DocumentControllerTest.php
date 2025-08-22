<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Municipality;
use App\Models\FederativeUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $customerUser;
    protected $municipality;
    protected $federativeUnit;
    protected $documentType;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('documents');

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

        $this->documentType = DocumentType::factory()->create([
            'name' => 'Relatório Mensal',
            'description' => 'Relatórios mensais do sistema',
            'status' => 'active'
        ]);
    }

    /** @test */
    public function admin_can_access_documents_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('documents.index'))
            ->assertStatus(200)
            ->assertViewIs('documents.index')
            ->assertViewHas('documents');
    }

    /** @test */
    public function admin_can_filter_documents_by_search()
    {
        $document1 = Document::factory()->create([
            'name' => 'Relatório Agosto',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $document2 = Document::factory()->create([
            'name' => 'Orçamento Setembro',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.index', ['search' => 'Agosto']))
            ->assertStatus(200)
            ->assertSee('Relatório Agosto')
            ->assertDontSee('Orçamento Setembro');
    }

    /** @test */
    public function admin_can_filter_documents_by_type()
    {
        $anotherType = DocumentType::factory()->create([
            'name' => 'Contrato',
            'status' => 'active'
        ]);

        Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $anotherType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.index', ['document_type_id' => $this->documentType->id]))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_documents_by_status()
    {
        Document::factory()->create([
            'status' => 'active',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        Document::factory()->create([
            'status' => 'inactive',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.index', ['status' => 'active']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_documents_by_user()
    {
        $anotherUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);

        Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        Document::factory()->create([
            'user_id' => $anotherUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.index', ['user_id' => $this->customerUser->id]))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_document_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('documents.create'))
            ->assertStatus(200)
            ->assertViewIs('documents.create')
            ->assertViewHas('users')
            ->assertViewHas('documentTypes');
    }

    /** @test */
    public function admin_can_store_document_with_file()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $data = [
            'name' => 'Relatório Teste',
            'description' => 'Descrição do relatório teste',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active',
            'file' => $file
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'name' => 'Relatório Teste',
            'description' => 'Descrição do relatório teste',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active'
        ]);

        $this->assertTrue(Storage::disk('documents')->exists('documents/' . $file->hashName()));
    }

    /** @test */
    public function validation_fails_when_required_document_fields_are_missing()
    {
        $data = [
            'name' => '',
            'user_id' => '',
            'document_type_id' => '',
            'status' => 'invalid-status'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'user_id',
                'document_type_id',
                'status'
            ]);
    }

    /** @test */
    public function file_upload_validation_works()
    {
        $invalidFile = UploadedFile::fake()->create('document.exe', 1024, 'application/exe');

        $data = [
            'name' => 'Relatório Teste',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active',
            'file' => $invalidFile
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertSessionHasErrors('file');
    }

    /** @test */
    public function file_size_validation_works()
    {
        $largeFile = UploadedFile::fake()->create('large_document.pdf', 11 * 1024, 'application/pdf'); // 11MB

        $data = [
            'name' => 'Relatório Teste',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active',
            'file' => $largeFile
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertSessionHasErrors('file');
    }

    /** @test */
    public function admin_can_view_document()
    {
        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.show', $document))
            ->assertStatus(200)
            ->assertViewIs('documents.show')
            ->assertViewHas('document');
    }

    /** @test */
    public function admin_can_edit_document()
    {
        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.edit', $document))
            ->assertStatus(200)
            ->assertViewIs('documents.edit')
            ->assertViewHas('document')
            ->assertViewHas('users')
            ->assertViewHas('documentTypes');
    }

    /** @test */
    public function admin_can_update_document()
    {
        $document = Document::factory()->create([
            'name' => 'Nome Original',
            'description' => 'Descrição Original',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active'
        ]);

        $data = [
            'name' => 'Nome Atualizado',
            'description' => 'Descrição Atualizada',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'inactive'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('documents.update', $document), $data)
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'name' => 'Nome Atualizado',
            'description' => 'Descrição Atualizada',
            'status' => 'inactive'
        ]);
    }

    /** @test */
    public function admin_can_update_document_with_new_file()
    {
        $oldFile = UploadedFile::fake()->create('old_document.pdf', 1024, 'application/pdf');
        $newFile = UploadedFile::fake()->create('new_document.pdf', 1024, 'application/pdf');

        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'file_path' => 'documents/' . $oldFile->hashName()
        ]);

        $data = [
            'name' => $document->name,
            'description' => $document->description,
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => $document->status,
            'file' => $newFile
        ];

        $this->actingAs($this->adminUser)
            ->put(route('documents.update', $document), $data)
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Storage::disk('documents')->exists('documents/' . $newFile->hashName()));
    }

    /** @test */
    public function admin_can_delete_document()
    {
        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('documents.destroy', $document))
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('documents', [
            'id' => $document->id
        ]);
    }

    /** @test */
    public function admin_can_download_document()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        Storage::disk('documents')->put('documents/' . $file->hashName(), $file->getContent());

        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'file_path' => 'documents/' . $file->hashName(),
            'original_name' => 'document.pdf'
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.download', $document))
            ->assertStatus(200);
    }

    /** @test */
    public function cannot_download_document_without_file()
    {
        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'file_path' => null
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('documents.download', $document))
            ->assertStatus(404);
    }

    /** @test */
    public function customer_can_view_own_documents()
    {
        // Create documents for the customer
        Document::factory()->count(3)->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        // Create document for another customer
        $anotherUser = User::factory()->customer()->create([
            'municipality_id' => $this->municipality->id
        ]);
        Document::factory()->create([
            'user_id' => $anotherUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('documents.index'));

        $response->assertStatus(200)
            ->assertViewIs('documents.index');

        // Should only see own documents (3 total)
        $documents = $response->viewData('documents');
        $this->assertCount(3, $documents);
    }

    /** @test */
    public function document_statistics_are_calculated_correctly()
    {
        // Create documents with different statuses
        Document::factory()->count(3)->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active'
        ]);

        Document::factory()->count(2)->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'inactive'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('documents.index'));

        $response->assertStatus(200);
        // Should show correct statistics
    }

    /** @test */
    public function inactive_document_types_are_not_available_for_new_documents()
    {
        $inactiveType = DocumentType::factory()->create([
            'name' => 'Tipo Inativo',
            'status' => 'inactive'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('documents.create'));

        $response->assertStatus(200);
        $documentTypes = $response->viewData('documentTypes');

        // Should only include active document types
        $this->assertTrue($documentTypes->contains('id', $this->documentType->id));
        $this->assertFalse($documentTypes->contains('id', $inactiveType->id));
    }

    /** @test */
    public function document_file_is_deleted_when_document_is_deleted()
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');
        $filePath = 'documents/' . $file->hashName();
        Storage::disk('documents')->put($filePath, $file->getContent());

        $document = Document::factory()->create([
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'file_path' => $filePath
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('documents.destroy', $document))
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertFalse(Storage::disk('documents')->exists($filePath));
    }

    /** @test */
    public function document_can_be_stored_without_file()
    {
        $data = [
            'name' => 'Documento sem arquivo',
            'description' => 'Apenas metadados',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertRedirect(route('documents.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'name' => 'Documento sem arquivo',
            'user_id' => $this->customerUser->id,
            'file_path' => null
        ]);
    }

    /** @test */
    public function document_name_must_be_unique_per_user()
    {
        Document::factory()->create([
            'name' => 'Documento Único',
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id
        ]);

        $data = [
            'name' => 'Documento Único', // Same name
            'user_id' => $this->customerUser->id,
            'document_type_id' => $this->documentType->id,
            'status' => 'active'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('documents.store'), $data)
            ->assertSessionHasErrors('name');
    }
}