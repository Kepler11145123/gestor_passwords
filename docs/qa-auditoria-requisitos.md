# SecureVault: verificación de requisitos y auditoría QA/seguridad

**Alcance:** revisión estática del código, rutas, migraciones, configuración de ejemplo y pruebas disponibles. Se incluye una compilación de frontend. No se pudo validar el esquema de Neon en ejecución ni el envío SMTP real. La imagen de requisitos se trató como especificación del usuario.

## Resumen ejecutivo

La aplicación contiene autenticación, verificación de correo por código, bóvedas, credenciales cifradas con Laravel, separación por usuario en consultas de la aplicación, PIN de interfaz, recuperación de contraseña, informe PDF, carga de avatar, gráficos y una API propia. No todos los requisitos están demostrados como terminados: falta validar migraciones en Neon, el endpoint API depende de sesión web en vez de autenticación apropiada para consumidores externos, y el índice de credenciales redirige a bóvedas.

**Riesgos prioritarios:** hay valores que parecen secretos reales en el `.env.example` versionado; la migración de propiedad no reasigna filas antiguas cuando existen varios usuarios; el PIN de cuatro dígitos no tiene límite de intentos; y el cifrado de credenciales depende de `APP_KEY`, sin envoltura de clave con el PIN. La suite de PHPUnit no pudo arrancar en este entorno.

## Matriz de requisitos

| Requisito | Estado | Evidencia y observaciones |
|---|---|---|
| Laravel 12 | **No cumple exactamente** | `composer.json` requiere `laravel/framework ^13.17`, no Laravel 12. Si la rúbrica exige la versión 12, es un incumplimiento formal aunque el código use Laravel. |
| Equipo de 1 a 3 personas | **No verificable en código** | Hace falta declarar integrantes/equipo en la entrega o sustentación. |
| Vistas con estilo heredado de layouts | **Cumple en código** | Vistas de aplicación usan `resources/views/layouts/app.blade.php`; acceso/registro usan `layouts/auth.blade.php`. |
| Base de datos admitida | **Parcial / despliegue pendiente** | La aplicación está configurada para PostgreSQL/Neon. Migraciones existen, pero el estado actual de Neon no pudo verificarse desde este entorno por fallo de resolución DNS. Además, el error reportado de `vault_pin_prompt_dismissed` confirma que esa migración aún no se había aplicado a la base usada por la aplicación. |
| Landing, marca SecureVault y elementos visuales propios | **Cumple en código** | `resources/views/landing.blade.php`, `components/brand-logo.blade.php` y `components/icon.blade.php`. La landing ya no contiene el texto promocional AES-256; quedan menciones técnicas en comentarios/migraciones que no se muestran al usuario. |
| Formularios personalizados de registro/login | **Cumple en código** | `resources/views/auth/register.blade.php`, `login.blade.php` y sus controladores. Registro exige contraseña de 12 caracteres con minúscula, mayúscula, número y símbolo. |
| Confirmar que el correo pertenece al usuario | **Cumple por código, SMTP no probado** | Registro envía código de seis dígitos; `EmailVerificationController` guarda hash con vencimiento, verifica y limpia el código. No se puede confirmar que el proveedor SMTP de producción entrega los mensajes. Una validación de formato/dominio no prueba que el buzón exista; el código enviado sí demuestra acceso a ese buzón. |
| Recuperación de contraseña | **Implementada en código, correo no probado** | `ForgotPasswordController` usa el broker de Laravel; vistas y notificación propias en `resources/views/auth/*` y `app/Notifications/`. Falta probar entrega SMTP real y el flujo completo en producción. |
| Rutas protegidas y sesión aislada por usuario | **Cumple mayormente en controladores** | Rutas de aplicación usan `auth` y `verified`; controladores consultan categorías/credenciales mediante relaciones del usuario actual y validan que la categoría pertenezca a ese usuario. No hay pruebas de integración de aislamiento entre dos cuentas. |
| Migraciones para el esquema | **Parcial / no confirmado en Neon** | Hay migraciones de usuarios, categorías, credenciales, propiedad de usuario, PIN, códigos de correo y preferencia de PIN. La columna faltante reportada evidencia divergencia entre código y base de datos. |
| Modelos y controladores | **Cumple en estructura** | Modelos `User`, `Categoria`, `Credencial` y controladores separados por funcionalidad. |
| Dashboard identifica usuario activo y permite navegar/cerrar sesión | **Cumple en código** | `dashboard/index.blade.php` muestra nombre/avatar; navegación y logout en `layouts/app.blade.php`. |
| DataTables para dos tablas | **Parcial** | La tabla de bóvedas en `categorias/index.blade.php` sí inicializa DataTables. La tabla de credenciales del índice tiene vista e inicialización, pero `CredencialController@index()` redirige a bóvedas, por lo que esa vista no se sirve desde su ruta. La tabla dentro de una bóveda no inicializa DataTables. |
| Editar perfil | **Cumple en código** | `PerfilController` y `resources/views/perfil/edit.blade.php`: nombre, correo, teléfono, avatar, contraseña y seguridad del PIN. |
| Gráficos en alguna vista | **Cumple en código** | Dashboard y bóvedas dibujan gráficos Chart.js con datos filtrados al usuario activo. |
| CRUD de dos tablas | **Implementado en rutas/controladores** | `CategoriaController` y `CredencialController` exponen operaciones CRUD. La ejecución integral no quedó verificada por la suite bloqueada. |
| API propia consumible desde fuera | **Parcial** | Existe `/api/v1/estadisticas` y `/api/v1/bovedas` en `routes/api.php`, pero están bajo middleware `web`, `auth` y `verified`: dependen de una sesión web/cookie y no ofrecen autenticación de API (por ejemplo, token). Esto limita el consumo desde clientes externos independientes. |
| Consumir API de terceros | **Cumple en código** | `HibpController` consulta Have I Been Pwned con el esquema k-anonymity: envía el prefijo SHA-1 y compara sufijos en el servidor. La disponibilidad externa no se probó en esta auditoría. |
| Descargar informe PDF | **Cumple en código** | `PdfController` primero pide contraseña maestra, luego presenta confirmación y descarga tras un clic explícito; la autorización vence a los cinco minutos y se consume al descargar. |
| Subir imágenes | **Cumple en código** | Avatar con validación de tipo/tamaño, almacenamiento y ruta protegida; menú permite cambiar y eliminar foto. No se probó visualmente en navegador. |
| Bóvedas con presets y nombre propio | **Cumple en código** | Formularios de bóveda y credencial ofrecen sugerencias editables en `datalist`; el usuario puede escribir otro nombre. Asociación y consultas usan `categoria_id` más `user_id`. |
| Contraseña de credencial a gusto del usuario | **Cumple en validación** | La contraseña almacenada se valida como string sin política de complejidad; los requisitos de robustez se aplican a la contraseña de acceso de la cuenta. |
| Cifrar datos sensibles de credenciales | **Cumple parcialmente** | `Credencial` cifra/descifra con `Crypt` de Laravel. El PIN protege acciones de interfaz, pero no envuelve criptográficamente la clave de cifrado; la protección de todos los datos depende de custodiar `APP_KEY`. |
| PIN opcional, reautenticación, recuperación y timeout | **Parcial** | Onboarding y ajuste de perfil están implementados; cambios desde perfil exigen contraseña maestra, hash con `Hash::make`, recuperación con contraseña maestra, y timeout de 20 segundos en la vista/30 segundos en sesión. Sin embargo, la ruta de verificación del PIN no tiene throttle y no existe key wrapping. El onboarding inicial no exige contraseña maestra al usuario que acaba de verificar su cuenta. |
| SMTP y correo con identidad SecureVault | **No verificable operacionalmente** | `config/mail.php` soporta SMTP y las notificaciones usan plantillas/nombre de SecureVault. Las credenciales de producción y el envío real deben probarse en el entorno desplegado. |
| Publicación en internet y ZIP con código/base | **No verificable en repositorio** | Se necesita enlace de despliegue y revisar que la entrega/ZIP no incluya `.env`, secretos, datos personales ni contraseñas reales. |

## Hallazgos negativos y riesgos

### Crítico — secretos de producción en `.env.example`

`.env.example` está versionado y contiene valores no vacíos que parecen incluir una clave de aplicación y credenciales de conexión a Neon, además de `APP_DEBUG=true`. Un archivo de ejemplo suele publicarse junto al repositorio; por tanto, debe tratarse como posible exposición, aunque no se haya confirmado si el repositorio es público. No se reproducen aquí los valores.

**Acción inmediata:** rotar la contraseña de base de datos expuesta y revisar accesos/logs; sustituir los valores del ejemplo por marcadores vacíos; retirar los secretos del historial Git si se publicaron. La rotación de `APP_KEY` requiere un plan especial: cambiarla sin migrar/re-encriptar los valores existentes puede dejar las credenciales guardadas ilegibles. Configurar `APP_DEBUG=false` en producción.

### Alto — PIN corto sin limitación de intentos

La credencial de PIN tiene solo 10.000 combinaciones posibles. La ruta `credenciales.reveal` no aplica middleware `throttle`; aunque el hash sea bcrypt, una sesión autenticada puede probar PINs repetidamente. Esto coincide con la decisión de producto de no bloquear ni temporizar, pero deja riesgo de fuerza bruta. El hash Bcrypt protege ante filtración directa de la columna, no convierte un PIN de cuatro dígitos en secreto fuerte.

**Acción recomendada:** reconsiderar el requisito de cero limitación; como mínimo, registrar intentos, añadir controles de abuso progresivos o exigir contraseña maestra después de una cantidad pequeña de fallos. Si la regla de no bloquear es inamovible, aceptar/documentar formalmente el riesgo.

### Alto — migración de propiedad deja registros antiguos sin dueño

`2026_11_05_000001_add_user_ownership_to_vault_data.php` agrega `user_id` nullable y solo reasigna filas históricas si hay exactamente un usuario. Con varias cuentas, categorías y credenciales antiguas se quedan con `user_id = NULL`; las consultas nuevas por propietario las ocultan y pueden parecer pérdida de datos. La columna nullable también permite registros huérfanos.

**Acción recomendada:** hacer respaldo; identificar y asignar manualmente dueños según evidencia fiable antes de exigir `NOT NULL`; auditar filas sin dueño y credenciales cuya categoría no pertenezca al mismo usuario. No ejecutar una asignación automática si la propiedad no se conoce.

### Alto — falta key wrapping del PIN

El PIN está guardado como hash y habilita/restringe rutas, mientras que el secreto de credencial se cifra con la clave global de Laravel (`Crypt`). No existe cifrado de una clave de bóveda derivada/envuelta por PIN. Por eso el PIN es una barrera de interfaz/autorización de servidor y no una segunda clave criptográfica independiente. El acceso al `APP_KEY` permite descifrar los secretos almacenados.

**Acción recomendada:** custodiar/rotar `APP_KEY` de forma planificada y cifrar backups. Si se requiere una arquitectura de conocimiento cero o key wrapping, hay que diseñarla con recuperación de claves y migración; no se logra añadiendo solo un modal PIN.

### Alto — las pruebas no arrancaron y hay pruebas desalineadas

`php artisan test` terminó antes de ejecutar casos: PHPUnit informa que no puede abrir `vendor/autoload.php`, aunque ese archivo sí existe y PHP puede cargarlo directamente. Además, una lectura estática muestra aserciones obsoletas en `ApplicationSmokeTest`: espera que `/credenciales` responda 200 aunque el controlador redirige, espera acceso anónimo a API protegida y espera descarga PDF directa aunque el flujo pide contraseña/confirmación.

**Acción recomendada:** corregir el bootstrap/configuración de PHPUnit y actualizar esos casos; añadir pruebas de aislamiento entre usuarios, verificación/caducidad del código, PIN (éxito/fallo/recuperación/timeout), protección de edición y autorización PDF. No se considera aprobado el comportamiento hasta que pasen.

### Medio — API no adecuada aún para consumidores externos

Las rutas API usan autenticación de sesión web. Un proceso externo no comparte automáticamente esa sesión ni tiene flujo de token. No exponer datos sin autenticación: definir un guard/token, scopes y respuestas limitadas si la API se debe consumir desde otro sistema.

### Medio — tabla de credenciales duplicada pero no accesible como índice

Existe `resources/views/credenciales/index.blade.php`, pero el método index redirige a bóvedas. Eso coincide con la intención posterior de clasificar por bóveda, pero deja código de vista/prueba muerto y no satisface claramente la rúbrica de DataTables para dos tablas: la tabla en detalle de bóveda es HTML sencillo.

### Medio — seguridad operativa y privacidad

- Confirmar que producción usa HTTPS y fuerza cookie `Secure`; `SESSION_SECURE_COOKIE` depende del entorno y no queda fijado en el archivo de ejemplo.
- Los avatares se escriben en el disco `public`; verificar que la ruta pública no permita saltarse el controlador autenticado mediante el symlink `/storage`.
- `avatarUrl()` puede enviar el nombre del usuario a `ui-avatars.com` para generar la imagen por defecto, con una petición a un tercero.
- SMTP y Have I Been Pwned dependen de servicios externos; requieren pruebas de disponibilidad, errores, timeout y configuración por entorno.

## Verificaciones ejecutadas

- PHP disponible: 8.3.33; Composer disponible: 2.10.2.
- `npm run build`: **correcto**. Vite emitió una advertencia no bloqueante sobre el paquete opcional `fontaine`.
- Sintaxis PHP y rutas principales: habían sido verificadas en la sesión de trabajo previa; este informe se basa también en inspección estática actual.
- `php artisan test`: **no ejecutado** porque PHPUnit no pudo abrir el archivo bootstrap; el proceso terminó con código 2.
- Conexión/migraciones Neon, envío SMTP, flujos en navegador, publicación y ZIP: **no verificados**.

## Orden de remediación sugerido

1. Rotar los secretos comprometidos y limpiar `.env.example`/historial; resolver `APP_DEBUG` y HTTPS/cookies de producción.
2. Respaldar Neon, aplicar migraciones pendientes y reconciliar filas sin propietario antes de marcar el aislamiento como validado.
3. Corregir PHPUnit y actualizar/priorizar pruebas automatizadas de seguridad y flujos críticos.
4. Decidir la política del PIN ante intentos masivos y definir si el producto necesita key wrapping real.
5. Ajustar API para consumidores externos y DataTables de acuerdo con la rúbrica.
6. Hacer una prueba de aceptación desplegada: registro/código SMTP, recuperación de cuenta, PIN, CRUD por dos usuarios, avatar, HIBP y PDF.
