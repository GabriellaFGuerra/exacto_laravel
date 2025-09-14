<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\ProviderService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar quantos serviços de fornecedores existem
        $existingCount = ProviderService::count();
        $this->command->info("Serviços de fornecedores existentes: {$existingCount}");
        
        // Se já existem serviços, perguntar se deve criar mais
        if ($existingCount > 0) {
            if (!$this->command->confirm('Deseja criar novas associações entre fornecedores e serviços?', true)) {
                $this->command->info('Operação cancelada.');
                return;
            }
        }
        
        // Obter todos os tipos de serviço
        $this->command->info("Obtendo tipos de serviço do banco...");
        $serviceTypes = ServiceType::pluck('id')->toArray();
        if (empty($serviceTypes)) {
            $this->command->error('Não existem tipos de serviço cadastrados. Execute o seeder de tipos de serviço primeiro.');
            return;
        }
        $this->command->info("Encontrados " . count($serviceTypes) . " tipos de serviço.");
        
        // Obter todos os fornecedores
        $this->command->info("Obtendo fornecedores do banco...");
        $providers = Provider::all();
        if ($providers->isEmpty()) {
            $this->command->error('Não existem fornecedores cadastrados. Execute o seeder de fornecedores primeiro.');
            return;
        }
        $this->command->info("Encontrados " . $providers->count() . " fornecedores.");
        
        // Associar serviços a cada fornecedor
        $this->command->info("Associando serviços aleatórios a cada fornecedor...");
        
        foreach ($providers as $provider) {
            try {
                // Selecionar 5 serviços aleatoriamente para cada fornecedor
                $selectedServices = array_rand(array_flip($serviceTypes), min(5, count($serviceTypes)));
                if (!is_array($selectedServices)) {
                    $selectedServices = [$selectedServices];
                }
                
                // Verificar quais serviços já estão associados a este fornecedor
                $existingServices = ProviderService::where('provider_id', $provider->id)
                    ->pluck('service_type_id')
                    ->toArray();
                
                $addedCount = 0;
                
                foreach ($selectedServices as $serviceId) {
                    // Não adicionar serviços que já estão associados
                    if (in_array($serviceId, $existingServices)) {
                        continue;
                    }
                    
                    ProviderService::create([
                        'provider_id' => $provider->id,
                        'service_type_id' => $serviceId
                    ]);
                    
                    $addedCount++;
                }
                
                $this->command->info("Associados {$addedCount} serviços ao fornecedor {$provider->name} com sucesso.");
                
            } catch (\Exception $e) {
                $this->command->error("Erro ao associar serviços ao fornecedor {$provider->name}: {$e->getMessage()}");
            }
        }
        
        $finalCount = ProviderService::count();
        $this->command->info("Total de associações entre fornecedores e serviços agora: {$finalCount}");
    }
}
