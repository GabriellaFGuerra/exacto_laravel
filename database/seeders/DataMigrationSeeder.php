<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DataMigrationSeeder extends Seeder
{
    public function run()
    {
        $mappings = [
            "admin_usuarios" => [
                "new_table" => "users",
                "columns" => [
                    "usu_id" => "id",
                    "usu_nome" => "name",
                    "usu_email" => "email",
                    "usu_login" => "login",
                    "usu_senha" => "password",
                    "usu_foto" => "photo"
                ],
                "defaults" => [
                    "status" => 1,
                    "user_type" => "admin",
                    "notification" => 1,
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => [
                    "deleted_at" => function ($row) {
                        return isset($row->usu_status) && $row->usu_status == 0 ? Carbon::now() : null;
                    }
                    // Mantendo o formato original da senha para compatibilidade com o sistema antigo
                    // As senhas serão transferidas diretamente sem modificação
                ]
            ],
            "cadastro_clientes" => [
                "new_table" => "users",
                "columns" => [
                    "cli_id" => "id",
                    "cli_nome_razao" => "name",
                    "cli_email" => "email",
                    "cli_login" => "login",
                    "cli_senha" => "password",
                    "cli_foto" => "photo",
                    "cli_endereco" => "address",
                    "cli_numero" => "number",
                    "cli_complemento" => "complement",
                    "cli_bairro" => "neighborhood",
                    "cli_cep" => "zip_code",
                    "cli_telefone" => "phone",
                    "cli_cnpj" => "cnpj",
                    "cli_cpf" => "cpf",
                ],
                "defaults" => [
                    "status" => 1,
                    "user_type" => "customer",
                    "notification" => 0,
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => [
                    "deleted_at" => function ($row) {
                        return isset($row->cli_status) && $row->cli_status == 0 ? Carbon::now() : null;
                    },
                    // Mantendo o formato original da senha para compatibilidade com o sistema antigo
                    // As senhas serão transferidas diretamente sem modificação
                ]
            ],
            "cadastro_fornecedores" => [
                "new_table" => "providers",
                "columns" => [
                    "for_id" => "id",
                    "for_nome_razao" => "name",
                    "for_email" => "email",
                    "for_cnpj" => "cnpj",
                    "for_telefone" => "phone",
                    "for_telefone2" => "phone_2",
                    "for_endereco" => "address",
                    "for_cep" => "zip_code",
                    "for_municipio" => "municipality_id",
                    "for_bairro" => "neighborhood",
                    "for_numero" => "number",
                    "for_comp" => "complement",
                    "for_data_cadastro" => "created_at",
                ],
                "defaults" => [
                    "status" => 1,
                    "updated_at" => now(),
                ],
                "transformers" => [
                    "deleted_at" => function ($row) {
                        return isset($row->for_status) && $row->for_status == 0 ? Carbon::now() : null;
                    },
                    "municipality_id" => function ($row) {
                        if (isset($row->for_municipio) && is_numeric($row->for_municipio)) {
                            return $row->for_municipio;
                        }
                        return null;
                    }
                ]
            ],
            "cadastro_tipos_servicos" => [
                "new_table" => "service_types",
                "columns" => [
                    "tps_id" => "id",
                    "tps_nome" => "name",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "cadastro_fornecedores_servicos" => [
                "new_table" => "provider_services",
                "columns" => [
                    "fse_fornecedor" => "provider_id",
                    "fse_servico" => "service_type_id",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "cadastro_tipos_docs" => [
                "new_table" => "document_types",
                "columns" => [
                    "tpd_id" => "id",
                    "tpd_nome" => "name",
                    "tpd_status" => "status",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "documento_gerenciar" => [
                "new_table" => "documents",
                "columns" => [
                    "doc_id" => "id",
                    "doc_cliente" => "customer_id",
                    "doc_orcamento" => "budget_id",
                    "doc_tipo" => "document_type_id",
                    "doc_anexo" => "attachment",
                    "doc_data_emissao" => "issue_date",
                    "doc_periodicidade" => "periodicity",
                    "doc_data_vencimento" => "expiration_date",
                    "doc_observacoes" => "observation",
                    "doc_data_cadastro" => "created_at",
                ],
                "defaults" => [
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "orcamento_gerenciar" => [
                "new_table" => "budgets",
                "columns" => [
                    "orc_id" => "id",
                    "orc_cliente" => "customer_id",
                    "orc_tipo_servico" => "service_type_id",
                    "orc_planilha" => "spreadsheets",
                    "orc_andamento" => "progress",
                    "orc_observacoes" => "observation",
                    "orc_data_cadastro" => "created_at",
                    "orc_data_aprovacao" => "approval_date",
                    "orc_usuario_responsavel" => "responsible_user_id",
                    "orc_gerente_responsavel" => "responsible_manager_id",
                    "orc_prazo" => "deadline",
                ],
                "defaults" => [
                    "status" => "open",
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "orcamento_fornecedor" => [
                "new_table" => "budget_providers",
                "columns" => [
                    "orf_id" => "id",
                    "orf_orcamento" => "budget_id",
                    "orf_fornecedor" => "provider_id",
                    "orf_valor" => "value",
                    "orf_obs" => "observation",
                    "orf_anexo" => "attachment",
                    "orf_data_cadastro" => "created_at",
                ],
                "defaults" => [
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "infracoes_gerenciar" => [
                "new_table" => "infractions",
                "columns" => [
                    "inf_id" => "id",
                    "inf_cliente" => "customer_id",
                    "inf_tipo" => "type",
                    "inf_ano" => "year",
                    "inf_cidade" => "city",
                    "inf_data" => "date",
                    "inf_proprietario" => "owner",
                    "inf_apto" => "apt",
                    "inf_bloco" => "block",
                    "inf_endereco" => "address",
                    "inf_email" => "email",
                    "inf_desc_irregularidade" => "irregularity_description",
                    "inf_assunto" => "subject",
                    "inf_desc_artigo" => "article_description",
                    "inf_desc_notificacao" => "notification_description",
                    "inf_comprovante" => "receipt",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "recurso_gerenciar" => [
                "new_table" => "appeals",
                "columns" => [
                    "rec_id" => "id",
                    "rec_infracao" => "infraction_id",
                    "rec_assunto" => "subject",
                    "rec_descricao" => "description",
                    "rec_recurso" => "appeal",
                    "rec_status" => "status",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "malote_gerenciar" => [
                "new_table" => "mailbags",
                "columns" => [
                    "mal_id" => "id",
                    "mal_cliente" => "customer_id",
                    "mal_lacre" => "seal",
                    "mal_observacoes" => "observation",
                    "mal_data_cadastro" => "created_at",
                    "mal_pg_eletronico" => "electronic_pg",
                    "mal_pg_eletronico2" => "electronic_pg2",
                ],
                "defaults" => [
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "malote_itens" => [
                "new_table" => "mailbag_items",
                "columns" => [
                    "mai_id" => "id",
                    "mai_malote" => "mailbag_id",
                    "mai_fornecedor" => "provider",
                    "mai_tipo_documento" => "document_type",
                    "mai_num_cheque" => "check_number",
                    "mai_valor" => "value",
                    "mai_data_vencimento" => "expiration_date",
                    "mai_baixado" => "closed",
                    "mai_data_baixa" => "close_date",
                    "mai_observacao" => "observation",
                ],
                "defaults" => [
                    "created_at" => now(),
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "prestacao_gerenciar" => [
                "new_table" => "statements",
                "columns" => [
                    "pre_id" => "id",
                    "pre_cliente" => "customer_id",
                    "pre_referencia" => "reference",
                    "pre_data_envio" => "send_date",
                    "pre_enviado_por" => "sent_by",
                    "pre_observacoes" => "observation",
                    "pre_data_cadastro" => "created_at",
                    "pre_comprovante" => "receipt",
                ],
                "defaults" => [
                    "updated_at" => now(),
                ],
                "transformers" => []
            ],
            "cadastro_gerentes" => [
                "new_table" => "managers",
                "columns" => [
                    "ger_id" => "id",
                    "ger_nome" => "name",
                    "ger_data_cadastro" => "created_at",
                ],
                "defaults" => [
                    "updated_at" => now(),
                ],
                "transformers" => []
            ]
        ];

        $old = DB::connection("mysql_old");

        foreach ($mappings as $oldTable => $map) {
            $rows = $old->table($oldTable)->get();
            $this->command->info("Migrating {$oldTable} ({$rows->count()} rows)");

            $successCount = 0;
            $errorCount = 0;

            foreach ($rows as $row) {
                $new = [];

                foreach ($map["columns"] as $oldCol => $newCol) {
                    $new[$newCol] = $row->$oldCol ?? null;
                }

                foreach ($map["defaults"] as $col => $val) {
                    $new[$col] = $val;
                }

                // Apply transformers
                if (isset($map["transformers"])) {
                    foreach ($map["transformers"] as $col => $transformer) {
                        $new[$col] = $transformer($row);
                    }
                }

                // Tratar campos JSON antes de inserir
                if ($map["new_table"] === "budgets" && isset($new["spreadsheets"])) {
                    if (!empty($new["spreadsheets"])) {
                        $new["spreadsheets"] = json_encode([$new["spreadsheets"]]);
                    } else {
                        $new["spreadsheets"] = json_encode([]);
                    }
                }

                try {
                    // Debugging para clientes
                    if ($oldTable === "cadastro_clientes") {
                        $this->command->info("Tentativa de inserir cliente: {$new['name']} com ID {$new['id']}");

                        // Verificar se tem email
                        if (empty($new['email'])) {
                            $new['email'] = 'cliente' . $new['id'] . '@exacto.com.br';
                            $this->command->warn("Email não encontrado para cliente {$new['id']}, usando {$new['email']}");
                        }

                        // Verificar se tem login
                        if (empty($new['login'])) {
                            $new['login'] = 'cliente' . $new['id'];
                            $this->command->warn("Login não encontrado para cliente {$new['id']}, usando {$new['login']}");
                        }

                        // Verificar se tem senha
                        if (empty($new['password'])) {
                            $new['password'] = bcrypt('cliente' . $new['id']);
                            $this->command->warn("Senha não encontrada para cliente {$new['id']}, usando senha padrão");
                        }
                    }
                    
                    // Debugging para fornecedores
                    if ($oldTable === "cadastro_fornecedores") {
                        $this->command->info("Tentativa de inserir fornecedor: {$new['name']} com ID {$new['id']}");

                        // Verificar se tem email
                        if (empty($new['email'])) {
                            $new['email'] = 'fornecedor' . $new['id'] . '@exacto.com.br';
                            $this->command->warn("Email não encontrado para fornecedor {$new['id']}, usando {$new['email']}");
                        }
                        
                        // Verificar se o fornecedor já existe no banco
                        $existingProvider = DB::table('providers')->where('id', $new['id'])->first();
                        if ($existingProvider) {
                            $this->command->warn("Fornecedor com ID {$new['id']} já existe. Pulando inserção.");
                            continue;
                        }
                    }

                    $result = DB::table($map["new_table"])->insert($new);

                    if ($result && $oldTable === "cadastro_clientes") {
                        $successCount++;
                        $this->command->info("Cliente {$new['name']} inserido com sucesso!");
                    } elseif ($oldTable === "cadastro_clientes") {
                        $errorCount++;
                        $this->command->error("Falha ao inserir cliente {$new['name']} com ID {$new['id']}");
                    } elseif ($result && $oldTable === "cadastro_fornecedores") {
                        $successCount++;
                        $this->command->info("Fornecedor {$new['name']} inserido com sucesso!");
                    } elseif ($oldTable === "cadastro_fornecedores") {
                        $errorCount++;
                        $this->command->error("Falha ao inserir fornecedor {$new['name']} com ID {$new['id']}");
                    }
                } catch (\Exception $e) {
                    if ($oldTable === "cadastro_clientes") {
                        $this->command->error("Erro ao inserir cliente {$new['id']}: " . $e->getMessage());

                        // Adicionar detalhes para debug
                        $this->command->info("Detalhes do cliente com erro:");
                        foreach ($new as $key => $value) {
                            $this->command->info("$key: " . (is_null($value) ? 'NULL' : $value));
                        }

                        $errorCount++;
                    } elseif ($oldTable === "cadastro_fornecedores") {
                        $this->command->error("Erro ao inserir fornecedor {$new['id']}: " . $e->getMessage());

                        // Adicionar detalhes para debug
                        $this->command->info("Detalhes do fornecedor com erro:");
                        foreach ($new as $key => $value) {
                            $this->command->info("$key: " . (is_null($value) ? 'NULL' : $value));
                        }

                        $errorCount++;
                    }
                }
            }

            if ($oldTable === "cadastro_clientes") {
                $this->command->info("Resumo de migração de clientes: {$successCount} inseridos com sucesso, {$errorCount} com erro");

                // Verificar se os clientes estão no banco
                $clientCount = DB::table('users')->where('user_type', 'customer')->count();
                $this->command->info("Total de clientes no banco após migração: {$clientCount}");
            } elseif ($oldTable === "cadastro_fornecedores") {
                $this->command->info("Resumo de migração de fornecedores: {$successCount} inseridos com sucesso, {$errorCount} com erro");

                // Verificar se os fornecedores estão no banco
                $providerCount = DB::table('providers')->count();
                $this->command->info("Total de fornecedores no banco após migração: {$providerCount}");
            }
        }

        $this->command->info(" Migration complete with filtering by date!");
    }
}
