# ERP SYSTEM

Este é um sistema ERP simples desenvolvido em PHP.

## Estrutura do Projeto
```bash
    /public - Arquivos públicos (CSS, JS, etc.)
    /app - Controladores, Modelos, Views e lógica da aplicação
    /config - Configurações do banco de dados e outras configurações
    /webhook - Webhook para mudança do status do pedido.
    /routes - Rotas da api.
    /sessions - Gerenciamento da sessão em relação ao carrinho de compras.
    /emails - Serviço de envio de e-mails
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
DB_PORT=3306
DB_NAME=mini_erp
DB_USER=root
DB_PASSWORD=secret123

VIA_CEP=https://viacep.com.br

SENDER_MAIL=loja@exemplo.com

EMAIL_HOST=smtp.mailtrap.io
EMAIL_SMTP_AUTH=true
EMAIL_USERNAME=mockuser
EMAIL_PASSWORD=mockpassword
EMAIL_PORT=2525

WEBHOOK_TOKEN=secrettoken123
```

4. Execute o servidor PHP:
```bash
   php -S 127.0.0.1:8000 -t public
```

5. Utilizando tela feita com html:
```bash
   http://127.0.0.1:8000/products.html
```