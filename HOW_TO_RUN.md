# How to Run Fusion Sports

Este guia fornece um passo a passo para configurar e executar o projeto Fusion Sports no seu ambiente local.

---

## Passo a Passo

### 1. Instalar o XAMPP

Faça o download e instale o [XAMPP](https://www.apachefriends.org/index.html) no seu sistema.

### 2. Clonar o Repositório

Clone o repositório na pasta `htdocs` do XAMPP:
```bash
cd C:/xampp/htdocs
git clone https://github.com/KauaAissa/Projeto_FusionSports.git
```

### 3. Aplicar o Código SQL

1. Acesse o **phpMyAdmin** pelo XAMPP.
2. Importe o arquivo SQL localizado na pasta `sql` do repositório.

### 4. Alterações Necessárias no Código

#### a) Configuração do PayPal

No arquivo `paypal.php`, altere e adicione as credenciais nas seguintes linhas:
```php
$clientId = 'ADICIONE SUA CLIENT ID AQUI';
$clientSecret = 'ADICIONE SUA CLIENT SECRET AQUI';
```
Essas credenciais são necessárias para que a API do PayPal funcione corretamente.

#### b) Configuração do Suporte por E-mail

No arquivo `processar_contato.php`, altere a linha:
```php
$destinatario = "ADICIONE UM EMAIL AQUI";
```
Substitua pelo e-mail que deseja usar para receber mensagens de suporte enviadas pela página inicial.

#### c) Configuração do `php.ini` no XAMPP

No arquivo `php.ini`, localize a seção `[mail function]` e altere as seguintes linhas:
```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587

sendmail_from = "ADICIONE O EMAIL REMETENTE AQUI"
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
```

#### d) Configuração do `sendmail.ini` no XAMPP

No arquivo `sendmail.ini`, localizado em `C:\xampp\sendmail\`, altere as seguintes linhas:
```ini
smtp_server=smtp.gmail.com
smtp_port=587
smtp_ssl=auto
auth_username=ADICIONE O EMAIL REMETENTE AQUI
auth_password=ADICIONE A SENHA DE APLICATIVO AQUI
```
Essas alterações garantem que a funcionalidade de envio de e-mails pelo suporte funcione corretamente.

---

Após concluir todos os passos, inicie o servidor Apache e MySQL pelo XAMPP e acesse o projeto no navegador por meio de `http://localhost/Projeto_FusionSports`.
