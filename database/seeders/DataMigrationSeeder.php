<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DataMigrationSeeder extends Seeder
{
    public function run()
    {
        $mappings = [
            'cadastro_clientes' => [
                'new_table' => 'users',
                'columns' => [
                    'cli_id' => 'id',
                    'cli_nome_razao' => 'name',
                    'cli_email' => 'email',
                    'cli_senha' => 'password',
                    'cli_foto' => 'photo',
                ],
                'defaults' => [
                    'status' => 1,
                    'user_type' => 'customer',
                    'notification' => 0,
                    'deleted_at' => 0,
                ],
                'transformers' => [],
            ],
            'cadastro_fornecedores' => [
                'new_table' => 'providers',
                'columns' => [
                    'for_id' => 'id',
                    'for_nome_razao' => 'name',
                    'for_email' => 'email',
                    'for_cnpj' => 'cnpj',
                    'for_telefone' => 'phone',
                    'for_telefone2' => 'phone_2',
                    'for_endereco' => 'address',
                    'for_cep' => 'zip_code',
                    'for_municipio' => 'municipality_id',
                    'for_bairro' => 'neighborhood',
                    'for_numero' => 'number',
                    'for_comp' => 'complement',
                    'for_data_cadastro' => 'created_at',
                ],
                'defaults' => [
                    'deleted_at' => null,
                ],
                'transformers' => [
                    'deleted_at' => function ($row) {
                        return $row->for_status == 1 ? Carbon::now() : null;
                    }

                ],
            ],
            'cadastro_tipos_servicos' => [
                'new_table' => 'service_types',
                'columns' => [
                    'tps_id' => 'id',
                    'tps_nome' => 'name',
                    'tps_status' => 'status',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'cadastro_fornecedores_servicos' => [
                'new_table' => 'provider_services',
                'columns' => [
                    'fse_fornecedor' => 'provider_id',
                    'fse_servico' => 'service_type_id',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'cadastro_tipos_docs' => [
                'new_table' => 'document_types',
                'columns' => [
                    'tpd_id' => 'id',
                    'tpd_nome' => 'name',
                    'tpd_status' => 'status',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'documento_gerenciar' => [
                'new_table' => 'documents',
                'columns' => [
                    'doc_id' => 'id',
                    'doc_cliente' => 'customer_id',
                    'doc_orcamento' => 'budget_id',
                    'doc_tipo' => 'document_type_id',
                    'doc_anexo' => 'attachment',
                    'doc_data_emissao' => 'issue_date',
                    'doc_periodicidade' => 'periodicity',
                    'doc_data_vencimento' => 'expiration_date',
                    'doc_observacoes' => 'observation',
                    'doc_data_cadastro' => 'created_at',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'orcamento_gerenciar' => [
                'new_table' => 'budgets',
                'columns' => [
                    'orc_id' => 'id',
                    'orc_cliente' => 'customer_id',
                    'orc_tipo_servico' => 'service_type_id',
                    'orc_planilha' => 'spreadsheet',
                    'orc_andamento' => 'progress',
                    'orc_observacoes' => 'observation',
                    'orc_data_cadastro' => 'created_at',
                    'orc_data_aprovacao' => 'approval_date',
                    'orc_usuario_responsavel' => 'responsible_user_id',
                    'orc_gerente_responsavel' => 'responsible_manager_id',
                    'orc_prazo' => 'deadline',
                ],
                'defaults' => [
                    'status' => 'open',
                ],
                'transformers' => [],
            ],
            'orcamento_fornecedor' => [
                'new_table' => 'budget_providers',
                'columns' => [
                    'orf_id' => 'id',
                    'orf_orcamento' => 'budget_id',
                    'orf_fornecedor' => 'provider_id',
                    'orf_valor' => 'value',
                    'orf_obs' => 'observation',
                    'orf_anexo' => 'attachment',
                    'orf_data_cadastro' => 'created_at',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'infracoes_gerenciar' => [
                'new_table' => 'infractions',
                'columns' => [
                    'inf_id' => 'id',
                    'inf_cliente' => 'customer_id',
                    'inf_tipo' => 'type',
                    'inf_ano' => 'year',
                    'inf_cidade' => 'city',
                    'inf_data' => 'date',
                    'inf_proprietario' => 'owner',
                    'inf_apto' => 'apt',
                    'inf_bloco' => 'block',
                    'inf_endereco' => 'address',
                    'inf_email' => 'email',
                    'inf_desc_irregularidade' => 'irregularity_description',
                    'inf_assunto' => 'subject',
                    'inf_desc_artigo' => 'article_description',
                    'inf_desc_notificacao' => 'notification_description',
                    'inf_comprovante' => 'receipt',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'recurso_gerenciar' => [
                'new_table' => 'appeals',
                'columns' => [
                    'rec_id' => 'id',
                    'rec_infracao' => 'infraction_id',
                    'rec_assunto' => 'subject',
                    'rec_descricao' => 'description',
                    'rec_recurso' => 'appeal',
                    'rec_status' => 'status',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'malote_gerenciar' => [
                'new_table' => 'mailbags',
                'columns' => [
                    'mal_id' => 'id',
                    'mal_cliente' => 'customer_id',
                    'mal_lacre' => 'seal',
                    'mal_observacoes' => 'observation',
                    'mal_data_cadastro' => 'created_at',
                    'mal_pg_eletronico' => 'electronic_pg',
                    'mal_pg_eletronico2' => 'electronic_pg2',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'malote_itens' => [
                'new_table' => 'mailbag_items',
                'columns' => [
                    'mai_id' => 'id',
                    'mai_malote' => 'mailbag_id',
                    'mai_fornecedor' => 'provider',
                    'mai_tipo_documento' => 'document_type',
                    'mai_num_cheque' => 'check_number',
                    'mai_valor' => 'value',
                    'mai_data_vencimento' => 'expiration_date',
                    'mai_baixado' => 'closed',
                    'mai_data_baixa' => 'close_date',
                    'mai_observacao' => 'observation',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'prestacao_gerenciar' => [
                'new_table' => 'statements',
                'columns' => [
                    'pre_id' => 'id',
                    'pre_cliente' => 'customer_id',
                    'pre_referencia' => 'reference',
                    'pre_data_envio' => 'send_date',
                    'pre_enviado_por' => 'sent_by',
                    'pre_observacoes' => 'observation',
                    'pre_data_cadastro' => 'created_at',
                    'pre_comprovante' => 'receipt',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
            'cadastro_gerentes' => [
                'new_table' => 'managers',
                'columns' => [
                    'ger_id' => 'id',
                    'ger_nome' => 'name',
                    'ger_data_cadastro' => 'created_at',
                ],
                'defaults' => [],
                'transformers' => [],
            ],
        ];

        $old = DB::connection('mysql_old');

        foreach ($mappings as $oldTable => $map) {
            $rows = $old->table($oldTable)->get();
            $this->command->info("Migrating {$oldTable} ({$rows->count()} rows)");

            foreach ($rows as $row) {
                $new = [];

                foreach ($map['columns'] as $oldCol => $newCol) {
                    $new[$newCol] = $row->$oldCol ?? null;
                }

                foreach ($map['defaults'] as $col => $val) {
                    $new[$col] = $val;
                }

                // Apply transformers
                if (isset($map['transformers'])) {
                    foreach ($map['transformers'] as $col => $transformer) {
                        $new[$col] = $transformer($row);
                    }
                }

                DB::table($map['new_table'])->insertOrIgnore($new);
            }
        }

        $this->command->info('✅ Migration complete with filtering by date!');
    }
}