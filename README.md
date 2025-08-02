# Sistema Exacto Laravel

![PHP](https://img.shields.io/badge/PHP-81.8%25-blue)
![Blade](https://img.shields.io/badge/Blade-18%25-green)
![JavaScript](https://img.shields.io/badge/JavaScript-0.2%25-yellow)

## Sobre o Projeto

O Sistema Exacto é uma aplicação Laravel desenvolvida para gestão administrativa de condomínios, permitindo controle de orçamentos, documentos, infrações, malotes e diversos outros recursos administrativos. A plataforma facilita o gerenciamento de clientes, prestadores de serviços e processos internos, proporcionando uma interface intuitiva e funcionalidades robustas.

## Funcionalidades Principais

- **Gestão de Usuários**
  - Diferentes tipos de usuários (administradores e clientes)
  - Controle de acesso baseado em permissões
  - Perfis personalizáveis com informações detalhadas

- **Gestão de Orçamentos**
  - Criação e acompanhamento de orçamentos
  - Vinculação com tipos de serviços
  - Atribuição de responsáveis e gerentes
  - Definição de prazos e status
  - Observações e histórico de progresso

- **Gestão de Documentos**
  - Upload e armazenamento de arquivos
  - Categorização por tipos
  - Controle de datas de emissão e validade
  - Alertas de vencimento
  - Associação com clientes e orçamentos

- **Gestão de Infrações e Recursos**
  - Registro detalhado de infrações
  - Controle de recursos administrativos
  - Acompanhamento de status e resultados
  - Armazenamento de documentação relacionada

- **Gestão de Malotes**
  - Registro de malotes e itens
  - Controle de valores
  - Acompanhamento de status de fechamento
  - Observações e detalhamentos

- **Gestão de Localidades**
  - Estrutura hierárquica de unidades federativas
  - Cadastro completo de municípios
  - Utilização em endereçamento de clientes e fornecedores

- **Gestão de Prestadores de Serviços**
  - Cadastro detalhado de fornecedores
  - Vinculação com tipos de serviços
  - Controle de orçamentos por fornecedor

## Requisitos do Sistema

- PHP >= 8.0
- Composer
- MySQL 5.7+ ou PostgreSQL 9.6+
- Node.js (v14+) e NPM
- Extensões PHP:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - Curl
  - ZIP
- Servidor Web (Apache/Nginx)
- Espaço em disco: mínimo 500MB para instalação básica

## Instalação

### Preparação do Ambiente

```bash
php -v
composer -V
mysql --version
node -v
npm -v
```

Configure seu servidor web para apontar para a pasta `public` do projeto.

### Instalação do Projeto

```bash
git clone https://github.com/GabriellaFGuerra/exacto_laravel.git
cd exacto_laravel
composer install
npm install && npm run dev
cp .env.example .env
```

Edite o `.env` com suas configurações:

```dotenv
APP_NAME="Sistema Exacto"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exacto
DB_USERNAME=root
DB_PASSWORD=
```

Siga os comandos abaixo para configurar o ambiente:

```bash
php artisan key:generate
mysql -u root -p -e "CREATE DATABASE exacto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
php artisan db:seed   # Opcional
chmod -R 775 storage bootstrap/cache
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

## Estrutura do Banco de Dados

### Usuários e Permissões

- **Users**
- **Managers**

### Orçamentos e Serviços

- **Budgets**
- **ServiceTypes**
- **ProviderService**

### Documentos

- **Documents**
- **DocumentTypes**

### Infrações e Recursos

- **Infractions**
- **Appeals**

### Malotes

- **Mailbags**

### Fornecedores

- **Providers**

## Manutenção

### Backup do Banco de Dados

```bash
mysqldump -u root -p exacto > backup-exacto-$(date +%Y%m%d).sql
mysql -u root -p exacto < backup-exacto-20250802.sql
```

### Atualização

```bash
git pull origin main
composer install --no-dev
npm install && npm run build
php artisan migrate
php artisan optimize:clear
```

### Comandos Úteis

```bash
php artisan status
php artisan cache:clear
composer dump-autoload
php artisan route:list
```

## Solução de Problemas

1. **Permissão em pastas**

   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Erro "Class not found"**

   ```bash
   composer dump-autoload
   ```

3. **Erro de conexão com banco**
   - Verifique `.env`
   - Verifique se o MySQL está ativo

4. **Estilos não carregando**

   ```bash
   npm run dev
   ```

5. **Erro em migrações**

   ```bash
   php artisan migrate:fresh
   # CUIDADO: apaga os dados existentes
   ```

## Contribuição

1. Fork o repositório
2. Crie uma branch: `git checkout -b feature/nome-da-feature`
3. Commit: `git commit -m 'Adiciona nova feature'`
4. Push: `git push origin feature/nome-da-feature`
5. Abra um Pull Request

## Contato

Gabriella Guerra - <gabriellafguerra21@gmail.com>  
Link do projeto: [https://github.com/GabriellaFGuerra/exacto_laravel](https://github.com/GabriellaFGuerra/exacto_laravel)

---

Desenvolvido em 2025 | Sistema Exacto
