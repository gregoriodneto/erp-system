# ERP SYSTEM

Este é um sistema ERP simples desenvolvido em PHP.

## Estrutura do Projeto
```bash
    /public - Arquivos públicos (CSS, JS, etc.)
    /app - Controladores, Modelos, Views e lógica da aplicação
    /config - Configurações do banco de dados e outras configurações
    /sql - Arquivos SQL de banco de dados
    /vendor - Dependências do Composer
    /bootstrap.php - Carregamento de variáveis de ambiente
    composer.json - Dependências e autoload do projeto
```

## Instalação

1. Clone o repositório:
```bash
   git clone <URL_do_repositório>
   cd erp-system
```
2. Instale as dependências:
```bash
   composer install
```
3. Crie o arquivo .env na raiz do projeto com as variáveis de ambiente do banco de dados:
```bash
   DB_HOST=localhost
   DB_USER=usuario
   DB_PASSWORD=senha
   DB_DATABASE=nome_do_banco
   DB_PORT=3306
```

4. Execute o servidor PHP:
```bash
   php -S 127.0.0.1:8000 -t public
```