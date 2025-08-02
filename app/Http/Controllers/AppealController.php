<?php

namespace App\Http\Controllers;

use App\Models\Infraction;
use App\Models\Appeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppealController extends Controller
{
    public function create(Infraction $infraction)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para criar recursos para esta infração.']);
            }

            return view('appeals.create', compact('infraction'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de recurso: ' . $exception->getMessage()]);
        }
    }

    public function store(Request $request, Infraction $infraction)
    {
        if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para criar recursos para esta infração.']);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'appeal' => 'nullable|file|mimes:pdf,doc,docx',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        try {
            $data = $request->except(['appeal']);
            $data['infraction_id'] = $infraction->id;

            if ($request->hasFile('appeal')) {
                $appeal_file = $request->file('appeal');
                $appeal_filename = $this->generateAppealFilename($infraction, $request->subject, $appeal_file);
                $appeal_directory = $this->getAppealDirectory($infraction);
                $appeal_path = $appeal_file->storeAs($appeal_directory, $appeal_filename, 'public');
                $data['appeal'] = $appeal_path;
            }

            $appeal = Appeal::create($data);

            return redirect()->route('infractions.show', $infraction)
                ->with('success', 'Recurso cadastrado com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao cadastrar o recurso: ' . $exception->getMessage()]);
        }
    }

    public function show(Infraction $infraction, Appeal $appeal)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para visualizar este recurso.']);
            }

            if ($appeal->infraction_id !== $infraction->id) {
                return redirect()->route('infractions.show', $infraction)
                    ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
            }

            return view('appeals.show', compact('infraction', 'appeal'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o recurso: ' . $exception->getMessage()]);
        }
    }

    public function edit(Infraction $infraction, Appeal $appeal)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para editar este recurso.']);
            }

            if ($appeal->infraction_id !== $infraction->id) {
                return redirect()->route('infractions.show', $infraction)
                    ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
            }

            return view('appeals.edit', compact('infraction', 'appeal'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de edição: ' . $exception->getMessage()]);
        }
    }

    public function update(Request $request, Infraction $infraction, Appeal $appeal)
    {
        if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para atualizar este recurso.']);
        }

        if ($appeal->infraction_id !== $infraction->id) {
            return redirect()->route('infractions.show', $infraction)
                ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'appeal' => 'nullable|file|mimes:pdf,doc,docx',
            'status' => 'required|string|in:pending,approved,rejected',
            'delete_appeal' => 'nullable|boolean',
        ]);

        try {
            $data = $request->except(['appeal', 'delete_appeal']);

            if ($request->has('delete_appeal') && $request->delete_appeal && !empty($appeal->appeal)) {
                Storage::disk('public')->delete($appeal->appeal);
                $data['appeal'] = null;
            }

            if ($request->hasFile('appeal')) {
                if (!empty($appeal->appeal)) {
                    Storage::disk('public')->delete($appeal->appeal);
                }

                $appeal_file = $request->file('appeal');
                $appeal_filename = $this->generateAppealFilename($infraction, $request->subject, $appeal_file);
                $appeal_directory = $this->getAppealDirectory($infraction);
                $appeal_path = $appeal_file->storeAs($appeal_directory, $appeal_filename, 'public');
                $data['appeal'] = $appeal_path;
            }

            $appeal->update($data);

            return redirect()->route('infractions.show', $infraction)
                ->with('success', 'Recurso atualizado com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar o recurso: ' . $exception->getMessage()]);
        }
    }

    public function destroy(Infraction $infraction, Appeal $appeal)
    {
        if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para excluir este recurso.']);
        }

        if ($appeal->infraction_id !== $infraction->id) {
            return redirect()->route('infractions.show', $infraction)
                ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
        }

        try {
            if (!empty($appeal->appeal)) {
                Storage::disk('public')->delete($appeal->appeal);
            }

            $appeal->delete();

            return redirect()->route('infractions.show', $infraction)
                ->with('success', 'Recurso excluído com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao excluir o recurso: ' . $exception->getMessage()]);
        }
    }

    public function downloadAppeal(Infraction $infraction, Appeal $appeal)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para baixar este arquivo.']);
            }

            if ($appeal->infraction_id !== $infraction->id) {
                return redirect()->route('infractions.show', $infraction)
                    ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
            }

            if (empty($appeal->appeal)) {
                return redirect()->back()->withErrors(['error' => 'Não há arquivo de recurso disponível.']);
            }

            if (!Storage::disk('public')->exists($appeal->appeal)) {
                return redirect()->back()->withErrors(['error' => 'O arquivo não foi encontrado no servidor.']);
            }

            $appeal_extension = pathinfo($appeal->appeal, PATHINFO_EXTENSION);
            $appeal_file_name = "appeal_{$appeal->id}_{$infraction->id}.{$appeal_extension}";

            $appeal_file_path = storage_path('app/public/' . $appeal->appeal);
            $appeal_mime_type = mime_content_type($appeal_file_path);

            return response()->download(
                $appeal_file_path,
                $appeal_file_name,
                ['Content-Type' => $appeal_mime_type]
            );
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao baixar o arquivo: ' . $exception->getMessage()]);
        }
    }

    private function generateAppealFilename($infraction, $subject, $appeal_file)
    {
        return sprintf(
            'appeal_%06d_%s_%s.%s',
            $infraction->id,
            Str::slug($subject, '_'),
            date('Ymd_His'),
            $appeal_file->getClientOriginalExtension()
        );
    }

    private function getAppealDirectory($infraction)
    {
        $appeal_year = date('Y');
        $appeal_month = date('m');

        return "appeals/{$appeal_year}/{$appeal_month}/{$infraction->id}";
    }

    public function updateStatus(Request $request, Infraction $infraction, Appeal $appeal)
    {
        if (auth()->user()->user_type !== 'admin') {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para atualizar o status deste recurso.']);
        }

        if ($appeal->infraction_id !== $infraction->id) {
            return redirect()->route('infractions.show', $infraction)
                ->withErrors(['error' => 'O recurso solicitado não pertence a esta infração.']);
        }

        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        try {
            $appeal->update([
                'status' => $request->status
            ]);

            return redirect()->route('infractions.show', $infraction)
                ->with('success', 'Status do recurso atualizado com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar o status do recurso: ' . $exception->getMessage()]);
        }
    }
}