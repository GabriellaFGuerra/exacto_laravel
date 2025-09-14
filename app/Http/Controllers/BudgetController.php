<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Provider;
use App\Models\BudgetProvider;
use App\Models\User;
use App\Models\ServiceType;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $budgets = Budget::where('responsible_user_id', auth()->user()->id)
                ->with(['customer', 'serviceType', 'responsibleManager'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('budgets.index', compact('budgets'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar os orçamentos: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $customers = User::where('user_type', 'customer')->where('status', 1)->get();
            $serviceTypes = ServiceType::where('status', 1)->get();
            $responsibleUsers = User::where('user_type', 'admin')->where('status', 1)->get();
            $managers = Manager::all();
            $providers = Provider::where('deleted_at', null)->get();

            return view('budgets.create', compact('customers', 'serviceTypes', 'responsibleUsers', 'managers', 'providers'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de criação: ' . $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_type_id' => 'required|exists:service_types,id',
            'custom_service_type' => 'nullable|string|max:255',
            'spreadsheets.*' => 'nullable|file|mimes:xlsx,xls,csv,pdf,doc,docx',
            'progress' => 'required|integer|min:0|max:100',
            'observation' => 'nullable|string|max:500',
            'approval_date' => 'nullable|date',
            'responsible_user_id' => 'required|exists:users,id',
            'responsible_manager_id' => 'nullable|exists:managers,id',
            'deadline' => 'nullable|date',
            'status' => 'required|string|in:open,approved,rejected,pending',
            // Novos campos para fornecedores
            'providers' => 'nullable|array',
            'providers.*.provider_id' => 'required|exists:providers,id',
            'providers.*.value' => 'nullable|numeric|min:0',
            'providers.*.observation' => 'nullable|string|max:255',
            'providers.*.attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
        ]);

        try {
            $data = $request->except(['spreadsheets', 'providers']);
            $data['spreadsheets'] = [];

            // Cria o orçamento antes para ter o ID
            $budget = Budget::create($data);

            // Processa os arquivos de planilha
            if ($request->hasFile('spreadsheets')) {
                $customer = User::find($request->customer_id);
                $serviceType = ServiceType::find($request->service_type_id);

                foreach ($request->file('spreadsheets') as $index => $file) {
                    $filename = $this->generateBudgetFilename($budget, $customer, $serviceType, $file, $index);
                    $directory = $this->getBudgetDirectory($budget);
                    $path = $file->storeAs($directory, $filename, 'public');
                    $data['spreadsheets'][] = $path;
                }

                // Atualiza o orçamento com os caminhos dos arquivos
                $budget->update(['spreadsheets' => $data['spreadsheets']]);
            }

            // Processa os fornecedores associados
            if ($request->has('providers')) {
                foreach ($request->providers as $providerData) {
                    $budgetProviderData = [
                        'provider_id' => $providerData['provider_id'],
                        'value' => $providerData['value'] ?? null,
                        'observation' => $providerData['observation'] ?? null,
                    ];

                    // Processa o anexo do fornecedor, se existir
                    if (isset($providerData['attachment'])) {
                        $file = $providerData['attachment'];
                        $provider = Provider::find($providerData['provider_id']);
                        $filename = $this->generateProviderFilename($budget, $provider, $providerData['value'] ?? 0, $file);
                        $directory = $this->getProviderDirectory($budget, $provider);
                        $path = $file->storeAs($directory, $filename, 'public');
                        $budgetProviderData['attachment'] = $path;
                    }

                    // Associa o fornecedor ao orçamento com os dados adicionais
                    $budget->providers()->attach($providerData['provider_id'], $budgetProviderData);
                }
            }

            return redirect()->route('budgets.index')->with('success', 'Orçamento criado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao criar o orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        try {
            // Carrega os relacionamentos necessários
            $budget->load([
                'customer',
                'serviceType',
                'responsibleUser',
                'responsibleManager',
                'providers',
                'documents'
            ]);

            return view('budgets.show', compact('budget'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        try {
            $customers = User::where('user_type', 'customer')->where('status', 1)->get();
            $serviceTypes = ServiceType::where('status', 1)->get();
            $responsibleUsers = User::where('user_type', 'admin')->where('status', 1)->get();
            $managers = Manager::all();
            $providers = Provider::where('deleted_at', null)->get();

            $budget->load('providers');

            return view('budgets.edit', compact('budget', 'customers', 'serviceTypes', 'responsibleUsers', 'managers', 'providers'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar o formulário de edição: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_type_id' => 'required|exists:service_types,id',
            'custom_service_type' => 'nullable|string|max:255',
            'spreadsheets.*' => 'nullable|file|mimes:xlsx,xls,csv,pdf,doc,docx',
            'progress' => 'required|integer|min:0|max:100',
            'observation' => 'nullable|string|max:500',
            'approval_date' => 'nullable|date',
            'responsible_user_id' => 'required|exists:users,id',
            'responsible_manager_id' => 'nullable|exists:managers,id',
            'deadline' => 'nullable|date',
            'status' => 'required|string|in:open,approved,rejected,pending',
            'delete_files' => 'nullable|array',
            'delete_files.*' => 'string',
            // Campos para fornecedores
            'providers' => 'nullable|array',
            'providers.*.id' => 'nullable|exists:budget_providers,id',
            'providers.*.provider_id' => 'required|exists:providers,id',
            'providers.*.value' => 'nullable|numeric|min:0',
            'providers.*.observation' => 'nullable|string|max:255',
            'providers.*.attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'providers.*.delete_attachment' => 'nullable|boolean',
            'delete_providers' => 'nullable|array',
            'delete_providers.*' => 'exists:budget_providers,id',
        ]);

        try {
            $data = $request->except(['spreadsheets', 'delete_files', 'providers', 'delete_providers']);

            // Handle file deletion
            if ($request->has('delete_files')) {
                $deleteFiles = $request->input('delete_files', []);
                $existingFiles = $budget->spreadsheets ?? [];
                foreach ($deleteFiles as $file) {
                    if (in_array($file, $existingFiles)) {
                        Storage::disk('public')->delete($file);
                        $existingFiles = array_diff($existingFiles, [$file]);
                    }
                }
                $data['spreadsheets'] = array_values($existingFiles);
            } else {
                $data['spreadsheets'] = $budget->spreadsheets ?? [];
            }

            // Handle multiple file upload
            if ($request->hasFile('spreadsheets')) {
                $customer = User::find($request->customer_id);
                $serviceType = ServiceType::find($request->service_type_id);

                foreach ($request->file('spreadsheets') as $index => $file) {
                    $filename = $this->generateBudgetFilename($budget, $customer, $serviceType, $file, $index);
                    $directory = $this->getBudgetDirectory($budget);
                    $path = $file->storeAs($directory, $filename, 'public');
                    $data['spreadsheets'][] = $path;
                }
            }

            // Update the budget
            $budget->update($data);

            // Handle provider deletion
            if ($request->has('delete_providers')) {
                foreach ($request->delete_providers as $budgetProviderId) {
                    $budgetProvider = BudgetProvider::find($budgetProviderId);
                    if ($budgetProvider && $budgetProvider->budget_id == $budget->id) {
                        // Delete attachment if exists
                        if (!empty($budgetProvider->attachment)) {
                            Storage::disk('public')->delete($budgetProvider->attachment);
                        }
                        $budgetProvider->delete();
                    }
                }
            }

            // Process providers
            if ($request->has('providers')) {
                foreach ($request->providers as $providerData) {
                    // Determine if this is an update or new record
                    if (!empty($providerData['id'])) {
                        // Updating existing budget provider
                        $budgetProvider = BudgetProvider::find($providerData['id']);

                        if ($budgetProvider && $budgetProvider->budget_id == $budget->id) {
                            $budgetProviderData = [
                                'provider_id' => $providerData['provider_id'],
                                'value' => $providerData['value'] ?? null,
                                'observation' => $providerData['observation'] ?? null,
                            ];

                            // Delete existing attachment if requested
                            if (isset($providerData['delete_attachment']) && $providerData['delete_attachment'] && !empty($budgetProvider->attachment)) {
                                Storage::disk('public')->delete($budgetProvider->attachment);
                                $budgetProviderData['attachment'] = null;
                            }

                            // Process new attachment if exists
                            if (isset($providerData['attachment'])) {
                                $file = $providerData['attachment'];
                                $provider = Provider::find($providerData['provider_id']);
                                $filename = $this->generateProviderFilename($budget, $provider, $providerData['value'] ?? 0, $file);
                                $directory = $this->getProviderDirectory($budget, $provider);
                                $path = $file->storeAs($directory, $filename, 'public');
                                $budgetProviderData['attachment'] = $path;
                            }

                            $budgetProvider->update($budgetProviderData);
                        }
                    } else {
                        // New budget provider
                        $budgetProviderData = [
                            'provider_id' => $providerData['provider_id'],
                            'value' => $providerData['value'] ?? null,
                            'observation' => $providerData['observation'] ?? null,
                        ];

                        // Process attachment if exists
                        if (isset($providerData['attachment'])) {
                            $file = $providerData['attachment'];
                            $provider = Provider::find($providerData['provider_id']);
                            $filename = $this->generateProviderFilename($budget, $provider, $providerData['value'] ?? 0, $file);
                            $directory = $this->getProviderDirectory($budget, $provider);
                            $path = $file->storeAs($directory, $filename, 'public');
                            $budgetProviderData['attachment'] = $path;
                        }

                        // Associate the provider with the budget
                        $budget->providers()->attach($providerData['provider_id'], $budgetProviderData);
                    }
                }
            }

            return redirect()->route('budgets.index')->with('success', 'Orçamento atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar o orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        try {
            // Remove attachments from storage
            if (!empty($budget->spreadsheets)) {
                foreach ($budget->spreadsheets as $spreadsheet) {
                    Storage::disk('public')->delete($spreadsheet);
                }
            }

            // Remove budget provider attachments
            foreach ($budget->budgetProviders as $budgetProvider) {
                if (!empty($budgetProvider->attachment)) {
                    Storage::disk('public')->delete($budgetProvider->attachment);
                }
            }

            $budget->delete();
            return redirect()->route('budgets.index')->with('success', 'Orçamento excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao excluir o orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Add a provider to a budget.
     */
    public function addProvider(Request $request, Budget $budget)
    {
        $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'value' => 'nullable|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
        ]);

        try {
            $budgetProviderData = [
                'provider_id' => $request->provider_id,
                'value' => $request->value,
                'observation' => $request->observation,
            ];

            // Process attachment if exists
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $provider = Provider::find($request->provider_id);
                $filename = $this->generateProviderFilename($budget, $provider, $request->value ?? 0, $file);
                $directory = $this->getProviderDirectory($budget, $provider);
                $path = $file->storeAs($directory, $filename, 'public');
                $budgetProviderData['attachment'] = $path;
            }

            $budget->providers()->attach($request->provider_id, $budgetProviderData);

            return redirect()->route('budgets.show', $budget)->with('success', 'Fornecedor adicionado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao adicionar fornecedor: ' . $e->getMessage()]);
        }
    }

    /**
     * Update a provider in a budget.
     */
    public function updateProvider(Request $request, Budget $budget, BudgetProvider $budgetProvider)
    {
        // Ensure the budget provider belongs to this budget
        if ($budgetProvider->budget_id != $budget->id) {
            return redirect()->back()->withErrors(['error' => 'Fornecedor não pertence a este orçamento.']);
        }

        $request->validate([
            'value' => 'nullable|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
            'delete_attachment' => 'nullable|boolean',
        ]);

        try {
            $budgetProviderData = [
                'value' => $request->value,
                'observation' => $request->observation,
            ];

            // Delete existing attachment if requested
            if ($request->has('delete_attachment') && $request->delete_attachment && !empty($budgetProvider->attachment)) {
                Storage::disk('public')->delete($budgetProvider->attachment);
                $budgetProviderData['attachment'] = null;
            }

            // Process new attachment if exists
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $provider = Provider::find($budgetProvider->provider_id);
                $filename = $this->generateProviderFilename($budget, $provider, $request->value ?? 0, $file);
                $directory = $this->getProviderDirectory($budget, $provider);
                $path = $file->storeAs($directory, $filename, 'public');
                $budgetProviderData['attachment'] = $path;
            }

            $budgetProvider->update($budgetProviderData);

            return redirect()->route('budgets.show', $budget)->with('success', 'Informações do fornecedor atualizadas com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar informações do fornecedor: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove a provider from a budget.
     */
    public function removeProvider(Budget $budget, BudgetProvider $budgetProvider)
    {
        // Ensure the budget provider belongs to this budget
        if ($budgetProvider->budget_id != $budget->id) {
            return redirect()->back()->withErrors(['error' => 'Fornecedor não pertence a este orçamento.']);
        }

        try {
            // Delete attachment if exists
            if (!empty($budgetProvider->attachment)) {
                Storage::disk('public')->delete($budgetProvider->attachment);
            }

            $budgetProvider->delete();

            return redirect()->route('budgets.show', $budget)->with('success', 'Fornecedor removido com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao remover fornecedor: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate a standardized filename for budget spreadsheets
     */
    private function generateBudgetFilename($budget, $customer, $serviceType, $file, $index = 0)
    {
        return sprintf(
            'budget_%06d_%s_%s_%s_%d.%s',
            $budget->id,                                // ID do orçamento com zeros à esquerda
            Str::slug($customer->name, '_'),           // Nome do cliente
            Str::slug($serviceType->name, '_'),        // Nome do tipo de serviço
            date('Ymd_His'),                           // Data e hora do upload
            $index,                                    // Índice do arquivo (caso múltiplos)
            $file->getClientOriginalExtension()        // Extensão original do arquivo
        );
    }

    /**
     * Generate a standardized filename for provider attachments
     */
    private function generateProviderFilename($budget, $provider, $value, $file)
    {
        // Formata o valor sem pontos ou vírgulas para incluir no nome
        $valueStr = number_format($value, 2, '', '_');

        return sprintf(
            'provider_%06d_%s_%s_%s.%s',
            $budget->id,                               // ID do orçamento com zeros à esquerda
            Str::slug($provider->name, '_'),           // Nome do fornecedor
            'value_' . $valueStr,                      // Valor formatado
            date('Ymd_His'),                           // Data e hora do upload
            $file->getClientOriginalExtension()        // Extensão original do arquivo
        );
    }

    /**
     * Get the directory path for budget files
     */
    private function getBudgetDirectory($budget)
    {
        $year = date('Y');
        $month = date('m');
        $customerId = $budget->customer_id;

        return "budgets/{$year}/{$month}/{$customerId}/{$budget->id}";
    }

    /**
     * Get the directory path for provider files
     */
    private function getProviderDirectory($budget, $provider)
    {
        $year = date('Y');
        $month = date('m');

        return "providers/{$year}/{$month}/{$budget->id}/{$provider->id}";
    }

    /**
     * Download a budget file
     */
    public function downloadFile($budgetId, $fileIndex)
    {
        try {
            $budget = Budget::findOrFail($budgetId);

            // Verificar se o usuário tem permissão para acessar esse orçamento
            if (auth()->user()->user_type != 'admin' && $budget->customer_id != auth()->user()->id && $budget->responsible_user_id != auth()->user()->id) {
                return redirect()->back()->withErrors(['error' => 'Você não tem permissão para acessar este arquivo.']);
            }

            if (!isset($budget->spreadsheets[$fileIndex])) {
                return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado.']);
            }

            $filePath = $budget->spreadsheets[$fileIndex];

            if (!Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado no servidor.']);
            }

            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            $fileName = "budget_{$budget->id}_file_{$fileIndex}.{$fileExtension}";

            $fullPath = public_path('storage/' . $filePath);
            return response()->download($fullPath, $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao baixar arquivo: ' . $e->getMessage()]);
        }
    }

    /**
     * Download a provider file
     */
    public function downloadProviderFile($budgetId, $providerId)
    {
        try {
            $budget = Budget::findOrFail($budgetId);
            $budgetProvider = BudgetProvider::where('budget_id', $budgetId)
                ->where('provider_id', $providerId)
                ->firstOrFail();

            // Verificar se o usuário tem permissão para acessar esse orçamento
            if (auth()->user()->user_type != 'admin' && $budget->customer_id != auth()->user()->id && $budget->responsible_user_id != auth()->user()->id) {
                return redirect()->back()->withErrors(['error' => 'Você não tem permissão para acessar este arquivo.']);
            }

            if (empty($budgetProvider->attachment)) {
                return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado.']);
            }

            $filePath = $budgetProvider->attachment;

            if (!Storage::disk('public')->exists($filePath)) {
                return redirect()->back()->withErrors(['error' => 'Arquivo não encontrado no servidor.']);
            }

            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            $provider = Provider::find($providerId);
            $providerName = Str::slug($provider->name, '_');
            $fileName = "provider_{$providerName}_budget_{$budgetId}.{$fileExtension}";

            $fullPath = public_path('storage/' . $filePath);
            return response()->download($fullPath, $fileName);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao baixar arquivo: ' . $e->getMessage()]);
        }
    }

    /**
     * List budgets for customer view
     */
    public function customerBudgets()
    {
        try {
            $budgets = Budget::where('customer_id', auth()->user()->id)
                ->with(['serviceType', 'responsibleUser', 'responsibleManager'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('budgets.customer', compact('budgets'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Falha ao carregar os orçamentos: ' . $e->getMessage()]);
        }
    }
}