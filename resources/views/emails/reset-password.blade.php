<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light"><title>Restablece tu contraseña | SecureVault</title></head>
<body style="margin:0;background:#f3f5fa;color:#181b35;font-family:Arial,Helvetica,sans-serif">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f5fa;padding:38px 14px"><tr><td align="center">
<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#fff;border:1px solid #e7e9f2;border-radius:22px;overflow:hidden">
    <tr><td style="padding:26px 34px;background:#171a38"><table role="presentation" cellspacing="0" cellpadding="0"><tr><td width="44" height="44" align="center" valign="middle" style="border-radius:14px;background:#5b5ce2;color:#fff;font-size:14px;font-weight:bold">SV</td><td style="padding-left:12px;color:#fff;font-size:20px;font-weight:bold;letter-spacing:-.5px">Secure<span style="color:#7ce0d7">Vault</span></td></tr></table></td></tr>
    <tr><td style="padding:38px 34px 24px">
        <p style="margin:0 0 10px;color:#5b5ce2;font-size:12px;font-weight:bold;letter-spacing:1.4px;text-transform:uppercase">Seguridad de la cuenta</p>
        <h1 style="margin:0 0 16px;font-size:27px;line-height:1.2;letter-spacing:-.7px">Restablece tu contraseña</h1>
        <p style="margin:0 0 24px;color:#6f748c;font-size:15px;line-height:1.7">Hola {{ $user->name }}, recibimos una solicitud para cambiar la contraseña de tu cuenta.</p>
        <table role="presentation" cellspacing="0" cellpadding="0"><tr><td bgcolor="#595add" style="border-radius:11px"><a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 22px;color:#fff;text-decoration:none;font-size:14px;font-weight:bold">Crear contraseña nueva</a></td></tr></table>
        <p style="margin:22px 0 0;color:#777c91;font-size:12px;line-height:1.6">Este enlace vence en {{ $expireMinutes }} minutos. Si no solicitaste el cambio, ignora este mensaje; tu contraseña no se modificará.</p>
    </td></tr>
    <tr><td style="padding:18px 34px 25px;border-top:1px solid #eef0f6;color:#9196aa;font-size:11px">SecureVault · Tus credenciales, bajo tu control.</td></tr>
</table>
</td></tr></table>
</body></html>
