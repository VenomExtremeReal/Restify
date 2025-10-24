# Configuração do Gmail para SMTP

## 1. Ativar Autenticação de 2 Fatores

1. Acesse: https://myaccount.google.com/security
2. Clique em "Verificação em duas etapas"
3. Siga as instruções para ativar

## 2. Gerar Senha de Aplicativo

1. Acesse: https://myaccount.google.com/apppasswords
2. Selecione "Aplicativo" → "Outro (nome personalizado)"
3. Digite: "Restify System - Password Reset"
4. Clique em "Gerar"
5. **Copie a senha gerada** (16 caracteres)

## 3. Configurar .env

Edite o arquivo `.env` com as credenciais oficiais do Restify:

```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=restifyapp.dpo@gmail.com
SMTP_PASSWORD=senha-de-16-caracteres-gerada
SMTP_FROM=restifyapp.dpo@gmail.com
SMTP_FROM_NAME=Restify - Suporte de Conta
SMTP_REPLY_TO=restifyapp.dpo@gmail.com
SMTP_ENCRYPTION=tls
```

**Importante:** Use a conta oficial restifyapp.dpo@gmail.com para manter a identidade profissional.

## 4. Testar Configuração

```bash
php test-email.php
```

## 5. Alternativas ao Gmail

### Outlook/Hotmail
```env
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
SMTP_USERNAME=seu-email@outlook.com
SMTP_PASSWORD=sua-senha
SMTP_ENCRYPTION=tls
```

### Yahoo Mail
```env
SMTP_HOST=smtp.mail.yahoo.com
SMTP_PORT=587
SMTP_USERNAME=seu-email@yahoo.com
SMTP_PASSWORD=senha-de-aplicativo
SMTP_ENCRYPTION=tls
```

## Troubleshooting

### Erro: "Username and Password not accepted"
- Verifique se a autenticação de 2 fatores está ativa
- Use senha de aplicativo, não a senha normal
- Verifique se o email está correto

### Erro: "Could not connect to SMTP host"
- Verifique conexão com internet
- Teste porta 465 com SSL se 587 não funcionar
- Verifique firewall/antivírus

### Erro: "SMTP Error: data not accepted"
- Verifique se o email remetente está correto
- Teste com email diferente como destinatário