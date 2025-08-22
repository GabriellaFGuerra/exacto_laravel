<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Appeal;
use App\Models\Infraction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppealControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $adminUser;
    protected $customer;
    protected $infraction;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::factory()->admin()->create();
        $this->customer = User::factory()->customer()->create();
        $this->infraction = Infraction::factory()->create([
            'user_id' => $this->customer->id
        ]);
    }

    /** @test */
    public function admin_can_access_appeals_index()
    {
        $this->actingAs($this->adminUser)
            ->get(route('appeals.index'))
            ->assertStatus(200)
            ->assertViewIs('appeals.index')
            ->assertViewHas('appeals');
    }

    /** @test */
    public function admin_can_filter_appeals_by_search()
    {
        Appeal::factory()->create(['reason' => 'Vehicle was stolen']);
        Appeal::factory()->create(['reason' => 'Wrong license plate']);

        $this->actingAs($this->adminUser)
            ->get(route('appeals.index', ['search' => 'stolen']))
            ->assertStatus(200)
            ->assertSee('Vehicle was stolen')
            ->assertDontSee('Wrong license plate');
    }

    /** @test */
    public function admin_can_filter_appeals_by_status()
    {
        Appeal::factory()->create(['status' => 'approved']);
        Appeal::factory()->create(['status' => 'pending']);

        $this->actingAs($this->adminUser)
            ->get(route('appeals.index', ['status' => 'approved']))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_filter_appeals_by_infraction()
    {
        $infraction1 = Infraction::factory()->create();
        $infraction2 = Infraction::factory()->create();

        Appeal::factory()->create(['infraction_id' => $infraction1->id]);
        Appeal::factory()->create(['infraction_id' => $infraction2->id]);

        $this->actingAs($this->adminUser)
            ->get(route('appeals.index', ['infraction_id' => $infraction1->id]))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_appeal_create_form()
    {
        $this->actingAs($this->adminUser)
            ->get(route('appeals.create'))
            ->assertStatus(200)
            ->assertViewIs('appeals.create')
            ->assertViewHas('infractions')
            ->assertViewHas('users');
    }

    /** @test */
    public function admin_can_store_appeal()
    {
        $documents = [
            UploadedFile::fake()->create('evidence1.pdf', 100),
            UploadedFile::fake()->create('evidence2.jpg', 100)
        ];

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'The vehicle was not in my possession at the time of the infraction.',
            'status' => 'pending',
            'supporting_documents' => $documents
        ];

        $this->actingAs($this->adminUser)
            ->post(route('appeals.store'), $data)
            ->assertRedirect(route('appeals.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('appeals', [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'The vehicle was not in my possession at the time of the infraction.',
            'status' => 'pending'
        ]);

        // Check if documents were stored
        $appeal = Appeal::where('reason', 'The vehicle was not in my possession at the time of the infraction.')->first();
        $this->assertNotNull($appeal->supporting_documents);

        $storedDocs = json_decode($appeal->supporting_documents, true);
        $this->assertCount(2, $storedDocs);

        foreach ($storedDocs as $doc) {
            $this->assertTrue(Storage::disk('public')->exists($doc));
        }
    }

    /** @test */
    public function validation_fails_when_required_appeal_fields_are_missing()
    {
        $data = [
            'infraction_id' => '',
            'user_id' => '',
            'reason' => '',
            'status' => ''
        ];

        $this->actingAs($this->adminUser)
            ->post(route('appeals.store'), $data)
            ->assertSessionHasErrors([
                'infraction_id',
                'user_id',
                'reason'
            ]);
    }

    /** @test */
    public function admin_can_view_appeal()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('appeals.show', $appeal))
            ->assertStatus(200)
            ->assertViewIs('appeals.show')
            ->assertViewHas('appeal');
    }

    /** @test */
    public function admin_can_edit_appeal()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id
        ]);

        $this->actingAs($this->adminUser)
            ->get(route('appeals.edit', $appeal))
            ->assertStatus(200)
            ->assertViewIs('appeals.edit')
            ->assertViewHas('appeal')
            ->assertViewHas('infractions')
            ->assertViewHas('users');
    }

    /** @test */
    public function admin_can_update_appeal()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'Original reason',
            'status' => 'pending'
        ]);

        $newDocument = UploadedFile::fake()->create('new_evidence.pdf', 100);

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'Updated reason for appeal',
            'status' => 'approved',
            'decision_date' => now()->format('Y-m-d\TH:i'),
            'decision_comments' => 'Appeal approved after review',
            'supporting_documents' => [$newDocument]
        ];

        $this->actingAs($this->adminUser)
            ->put(route('appeals.update', $appeal), $data)
            ->assertRedirect(route('appeals.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('appeals', [
            'id' => $appeal->id,
            'reason' => 'Updated reason for appeal',
            'status' => 'approved',
            'decision_comments' => 'Appeal approved after review'
        ]);

        // Check decision date was set
        $appeal->refresh();
        $this->assertNotNull($appeal->decision_date);
    }

    /** @test */
    public function admin_can_update_appeal_and_remove_documents()
    {
        // Create appeal with existing documents
        $existingDocs = ['documents/doc1.pdf', 'documents/doc2.jpg'];
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'supporting_documents' => json_encode($existingDocs)
        ]);

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => $appeal->reason,
            'status' => $appeal->status,
            'remove_documents' => [0] // Remove first document
        ];

        $this->actingAs($this->adminUser)
            ->put(route('appeals.update', $appeal), $data)
            ->assertRedirect(route('appeals.index'));

        $appeal->refresh();
        $remainingDocs = json_decode($appeal->supporting_documents, true);
        $this->assertCount(1, $remainingDocs);
        $this->assertEquals('documents/doc2.jpg', $remainingDocs[0]);
    }

    /** @test */
    public function admin_can_delete_appeal()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id
        ]);

        $this->actingAs($this->adminUser)
            ->delete(route('appeals.destroy', $appeal))
            ->assertRedirect(route('appeals.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('appeals', [
            'id' => $appeal->id
        ]);
    }

    /** @test */
    public function appeal_status_changes_are_tracked()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'status' => 'pending'
        ]);

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => $appeal->reason,
            'status' => 'approved',
            'decision_date' => now()->format('Y-m-d\TH:i'),
            'decision_comments' => 'Approved after thorough review'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('appeals.update', $appeal), $data)
            ->assertRedirect(route('appeals.index'));

        $appeal->refresh();
        $this->assertEquals('approved', $appeal->status);
        $this->assertNotNull($appeal->decision_date);
        $this->assertEquals('Approved after thorough review', $appeal->decision_comments);
    }

    /** @test */
    public function appeal_rejection_works_correctly()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'status' => 'pending'
        ]);

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => $appeal->reason,
            'status' => 'rejected',
            'decision_date' => now()->format('Y-m-d\TH:i'),
            'decision_comments' => 'Insufficient evidence provided'
        ];

        $this->actingAs($this->adminUser)
            ->put(route('appeals.update', $appeal), $data)
            ->assertRedirect(route('appeals.index'));

        $appeal->refresh();
        $this->assertEquals('rejected', $appeal->status);
        $this->assertEquals('Insufficient evidence provided', $appeal->decision_comments);
    }

    /** @test */
    public function file_upload_validation_works()
    {
        $invalidFile = UploadedFile::fake()->create('large_file.pdf', 15000); // 15MB

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'Test reason',
            'status' => 'pending',
            'supporting_documents' => [$invalidFile]
        ];

        $this->actingAs($this->adminUser)
            ->post(route('appeals.store'), $data)
            ->assertSessionHasErrors('supporting_documents.0');
    }

    /** @test */
    public function cannot_create_duplicate_appeal_for_same_infraction()
    {
        // Create existing appeal
        Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id
        ]);

        $data = [
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'Duplicate appeal attempt',
            'status' => 'pending'
        ];

        $this->actingAs($this->adminUser)
            ->post(route('appeals.store'), $data)
            ->assertSessionHasErrors();
    }

    /** @test */
    public function appeal_show_displays_related_information()
    {
        $appeal = Appeal::factory()->create([
            'infraction_id' => $this->infraction->id,
            'user_id' => $this->customer->id,
            'reason' => 'Test appeal reason',
            'status' => 'approved',
            'decision_comments' => 'Test decision comments'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('appeals.show', $appeal));

        $response->assertStatus(200)
            ->assertSee('Test appeal reason')
            ->assertSee('Test decision comments')
            ->assertSee($this->customer->name)
            ->assertSee($this->infraction->id);
    }
}
