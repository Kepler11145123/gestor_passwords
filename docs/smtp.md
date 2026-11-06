# Correo SMTP

La verificación de correo y los enlaces de recuperación usan el mailer SMTP de Laravel. Configura estas variables en `.env` del entorno y nunca subas credenciales reales al repositorio:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=usuario-smtp
MAIL_PASSWORD=secreto-smtp
MAIL_FROM_ADDRESS=cuentas@example.com
MAIL_FROM_NAME="SecureVault"
```

Usa los valores TLS/puerto que entregue tu proveedor SMTP. Después de cambiar la configuración, ejecuta `php artisan config:clear` y solicita un correo de verificación o recuperación para confirmar la entrega.
