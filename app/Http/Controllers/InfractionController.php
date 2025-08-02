<?php

namespace App\Http\Controllers;

use App\Models\Infraction;
use App\Models\Appeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InfractionController extends Controller
{
    public function index()
    {
        try {
            if (auth()->user()->user_type === 'admin') {
                $infractions = Infraction::with(['customer', 'appeals'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $infractions = Infraction::where('customer_id', auth()->user()->id)
                    ->with('appeals')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return view('infractions.index', compact('infractions'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar as infrações: ' . $exception->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $customers = User::where('user_type', 'customer')->where('status', 1)->get();
            return view('infractions.create', compact('customers'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de criação: ' . $exception->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'city' => 'required|string|max:255',
            'date' => 'required|date',
            'owner' => 'required|string|max:255',
            'apt' => 'nullable|string|max:50',
            'block' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'irregularity_description' => 'required|string',
            'subject' => 'required|string|max:255',
            'article_description' => 'required|string',
            'notification_description' => 'required|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        ]);

        try {
            $data = $request->except('receipt');

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $customer = User::find($request->customer_id);
                $filename = $this->generate_infraction_filename($customer, $request->type, $request->date, $file);
                $directory = $this->get_infraction_directory($customer);
                $path = $file->storeAs($directory, $filename, 'public');
                $data['receipt'] = $path;
            }

            $infraction = Infraction::create($data);

            return redirect()->route('infractions.show', $infraction)->with('success', 'Infração cadastrada com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao cadastrar a infração: ' . $exception->getMessage()]);
        }
    }

    public function show(Infraction $infraction)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para visualizar esta infração.']);
            }

            $infraction->load('appeals', 'customer');

            return view('infractions.show', compact('infraction'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar a infração: ' . $exception->getMessage()]);
        }
    }

    public function edit(Infraction $infraction)
    {
        try {
            if (auth()->user()->user_type !== 'admin') {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para editar infrações.']);
            }

            $customers = User::where('user_type', 'customer')->where('status', 1)->get();

            return view('infractions.edit', compact('infraction', 'customers'));
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de edição: ' . $exception->getMessage()]);
        }
    }

    public function update(Request $request, Infraction $infraction)
    {
        if (auth()->user()->user_type !== 'admin') {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para atualizar infrações.']);
        }

        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'city' => 'required|string|max:255',
            'date' => 'required|date',
            'owner' => 'required|string|max:255',
            'apt' => 'nullable|string|max:50',
            'block' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'irregularity_description' => 'required|string',
            'subject' => 'required|string|max:255',
            'article_description' => 'required|string',
            'notification_description' => 'required|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx',
            'delete_receipt' => 'nullable|boolean',
        ]);

        try {
            $data = $request->except(['receipt', 'delete_receipt']);

            if ($request->has('delete_receipt') && $request->delete_receipt && !empty($infraction->receipt)) {
                Storage::disk('public')->delete($infraction->receipt);
                $data['receipt'] = null;
            }

            if ($request->hasFile('receipt')) {
                if (!empty($infraction->receipt)) {
                    Storage::disk('public')->delete($infraction->receipt);
                }

                $file = $request->file('receipt');
                $customer = User::find($request->customer_id);
                $filename = $this->generate_infraction_filename($customer, $request->type, $request->date, $file);
                $directory = $this->get_infraction_directory($customer);
                $path = $file->storeAs($directory, $filename, 'public');
                $data['receipt'] = $path;
            }

            $infraction->update($data);

            return redirect()->route('infractions.show', $infraction)->with('success', 'Infração atualizada com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar a infração: ' . $exception->getMessage()]);
        }
    }

    public function destroy(Infraction $infraction)
    {
        if (auth()->user()->user_type !== 'admin') {
            return redirect()->route('infractions.index')
                ->withErrors(['error' => 'Você não tem permissão para excluir infrações.']);
        }

        try {
            if (!empty($infraction->receipt)) {
                Storage::disk('public')->delete($infraction->receipt);
            }

            foreach ($infraction->appeals as $appeal) {
                if (!empty($appeal->appeal)) {
                    Storage::disk('public')->delete($appeal->appeal);
                }
            }

            $infraction->delete();

            return redirect()->route('infractions.index')->with('success', 'Infração excluída com sucesso.');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao excluir a infração: ' . $exception->getMessage()]);
        }
    }

    public function downloadReceipt(Infraction $infraction)
    {
        try {
            if (auth()->user()->user_type !== 'admin' && auth()->user()->id !== $infraction->customer_id) {
                return redirect()->route('infractions.index')
                    ->withErrors(['error' => 'Você não tem permissão para baixar este arquivo.']);
            }

            if (empty($infraction->receipt)) {
                return redirect()->back()->withErrors(['error' => 'Não há recibo disponível para esta infração.']);
            }

            if (!Storage::disk('public')->exists($infraction->receipt)) {
                return redirect()->back()->withErrors(['error' => 'O arquivo não foi encontrado no servidor.']);
            }

            $extension = pathinfo($infraction->receipt, PATHINFO_EXTENSION);
            $file_name = "infraction_{$infraction->id}_receipt.{$extension}";

            return response()->download(
                storage_path('app/public/' . $infraction->receipt),
                $file_name
            );
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => 'Falha ao baixar o arquivo: ' . $exception->getMessage()]);
        }
    }

    private function generate_infraction_filename($customer, $type, $date, $file)
    {
        $date = date('Ymd', strtotime($date));

        return sprintf(
            'infraction_%s_%s_%s_%s.%s',
            Str::slug($customer->name, '_'),
            Str::slug($type, '_'),
            $date,
            date('Ymd_His'),
            $file->getClientOriginalExtension()
        );
    }

    private function get_infraction_directory($customer)
    {
        $year = date('Y');
        $month = date('m');

        return "infractions/{$year}/{$month}/{$customer->id}";
    }
}