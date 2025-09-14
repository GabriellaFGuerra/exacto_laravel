<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se já existem tipos de serviço no banco
        $existingCount = ServiceType::count();
        $this->command->info("Tipos de serviço existentes: {$existingCount}");

        // Se não há tipos de serviço ou se o usuário confirmar a limpeza
        if ($existingCount == 0 || $this->command->confirm('Deseja limpar os tipos de serviço existentes e inserir novos?', false)) {
            if ($existingCount > 0) {
                ServiceType::truncate();
                $this->command->info('Tabela de tipos de serviço limpa.');
            }

            $serviceTypes = [
                ['name' => 'Recursos de Multas', 'status' => 1],
                ['name' => 'Regularização de Imóveis', 'status' => 1],
                ['name' => 'Licenciamento Ambiental', 'status' => 1],
                ['name' => 'Alvará de Funcionamento', 'status' => 1],
                ['name' => 'Assessoria Jurídica', 'status' => 1],
                ['name' => 'Processos Administrativos', 'status' => 1],
                ['name' => 'Obtenção de Certidões', 'status' => 1],
                ['name' => 'Registro de Marcas e Patentes', 'status' => 1],
                ['name' => 'Licença Sanitária', 'status' => 1],
                ['name' => 'Consultoria Tributária', 'status' => 1],
            ];

            foreach ($serviceTypes as $serviceType) {
                try {
                    ServiceType::create([
                        'name' => $serviceType['name'],
                        'status' => $serviceType['status'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $this->command->info("Tipo de serviço '{$serviceType['name']}' inserido com sucesso.");
                } catch (\Exception $e) {
                    $this->command->error("Erro ao inserir tipo de serviço '{$serviceType['name']}': {$e->getMessage()}");
                }
            }

            $this->command->info('Tipos de serviço inseridos com sucesso.');
        } else {
            $this->command->info('Mantendo os tipos de serviço existentes.');
        }
    }
}
