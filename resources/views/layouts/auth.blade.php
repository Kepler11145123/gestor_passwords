<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#12152d">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'Acceso') | SecureVault</title>
    <style>
        :root{--ink:#171a32;--muted:#737891;--line:#e7e9f2;--violet:#5b5ce2;--mint:#42cfc1}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;background:#f5f6fb;color:var(--ink);font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
        .ui-icon{width:21px;height:21px;flex:none}.auth-shell{min-height:100vh;display:grid;grid-template-columns:minmax(340px,.9fr) minmax(440px,1.1fr)}
        .auth-aside{position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between;padding:2.6rem 3.1rem;color:#fff;background:radial-gradient(ellipse at 80% 13%,rgba(107,105,235,.55),transparent 38%),linear-gradient(150deg,#171a38,#252a58 58%,#273a68)}
        .auth-aside:before,.auth-aside:after{content:"";position:absolute;border:1px solid rgba(153,224,224,.13);border-radius:50%;pointer-events:none}.auth-aside:before{width:480px;height:480px;right:-220px;top:27%}.auth-aside:after{width:340px;height:340px;right:-150px;top:36%}
        .auth-aside>*{position:relative;z-index:1}.securevault-brand{display:inline-flex;align-items:center;gap:.7rem;color:var(--ink);text-decoration:none;font-size:1.3rem;font-weight:800;letter-spacing:-.045em}.securevault-brand.is-light{color:#fff}.securevault-brand .securevault-mark{width:42px;height:42px;flex:none}.securevault-brand .securevault-word>span{color:#5b5ce2}.securevault-brand.is-light .securevault-word>span{color:#80e2da}
        .auth-story{max-width:520px;margin:auto 0;padding:4rem 0}.auth-eyebrow{display:inline-flex;align-items:center;gap:.55rem;color:#a8f0e7;font-size:.77rem;letter-spacing:.13em;text-transform:uppercase;font-weight:750}.auth-eyebrow:before{content:"";width:22px;height:1px;background:#80e2da}.auth-story h2{font-size:clamp(2.35rem,4vw,4rem);line-height:1.06;letter-spacing:-.055em;margin:1.2rem 0}.auth-story p{max-width:420px;color:#bbc2e0;line-height:1.75;font-size:1.02rem}.auth-aside-foot{color:#a0a8c7;font-size:.8rem}
        .auth-main{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem clamp(1.25rem,6vw,6rem)}.auth-mobile-brand{display:none}.auth-card{width:min(100%,470px);padding:clamp(1.5rem,4vw,3rem);border:1px solid rgba(225,227,239,.9);border-radius:24px;background:rgba(255,255,255,.94);box-shadow:0 24px 70px rgba(31,37,88,.09)}
        .auth-card h1{margin:0 0 .6rem;font-size:1.85rem;letter-spacing:-.045em}.auth-intro{color:var(--muted);line-height:1.65;margin:0 0 1.7rem;font-size:.95rem}.auth-form-group{margin:1rem 0}.auth-form-group label{display:block;font-size:.84rem;font-weight:700;margin-bottom:.45rem}.auth-form-group input{width:100%;height:48px;padding:.7rem .85rem;border:1px solid #dfe2ef;border-radius:11px;background:#fbfbfe;color:var(--ink);font:inherit;transition:.18s}.auth-form-group input:focus{outline:none;border-color:#8586eb;box-shadow:0 0 0 4px rgba(91,92,226,.12);background:#fff}.auth-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}.auth-check{display:flex;align-items:center;gap:.5rem;color:#555b78;font-size:.84rem}.auth-check input{accent-color:var(--violet)}.auth-between{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin:1.2rem 0}.auth-link{color:#5658d8;text-decoration:none;font-weight:650}.auth-link:hover{text-decoration:underline}.auth-btn{width:100%;height:49px;border:0;border-radius:12px;background:linear-gradient(135deg,#6869ec,#4f50d4);color:#fff;font:inherit;font-weight:750;box-shadow:0 10px 22px rgba(82,83,210,.2);cursor:pointer;transition:transform .18s,box-shadow .18s}.auth-btn:hover{transform:translateY(-1px);box-shadow:0 13px 26px rgba(82,83,210,.28)}.auth-btn-secondary{background:#f0f1fa;color:#454967;box-shadow:none;border:1px solid var(--line)}.auth-btn-secondary:hover{box-shadow:none}.auth-error{margin-top:.35rem;color:#bb334c;font-size:.8rem}.auth-note{padding:.85rem 1rem;border:1px solid #d8eee9;background:#f0faf8;color:#256a61;border-radius:12px;margin:1rem 0;font-size:.88rem;line-height:1.5}.auth-note.error{border-color:#f4d5dc;background:#fff5f6;color:#a6334b}.auth-footer{color:#777d95;font-size:.86rem;text-align:center;margin:1.4rem 0 0}.auth-footer a{margin-left:.3rem}.auth-back{margin-top:1.25rem;color:#8a8fa5;font-size:.78rem}.auth-back a{color:#6f7390;text-decoration:none}
        .verify-icon{width:74px;height:74px;display:grid;place-items:center;border-radius:23px;background:linear-gradient(145deg,#eeefff,#e4faf6);color:#5659d4;margin-bottom:1.4rem}.verify-icon .ui-icon{width:35px;height:35px}.auth-email-pill{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem .8rem;border:1px solid var(--line);border-radius:10px;color:#555b78;background:#fafaff;font-size:.88rem;margin:.3rem 0 1.2rem}
        @media(max-width:900px){.auth-shell{grid-template-columns:1fr}.auth-aside{display:none}.auth-main{min-height:100vh;padding:1.25rem}.auth-mobile-brand{display:block;margin-bottom:1.1rem}.auth-card{padding:clamp(1.4rem,6vw,2.4rem)}}
        @media(max-width:480px){.auth-grid{grid-template-columns:1fr;gap:0}.auth-card{border-radius:19px}.auth-between{align-items:flex-start;flex-direction:column}}
    </style>
</head>
<body>
<div class="auth-shell">
    <aside class="auth-aside">
        <x-brand-logo :href="route('landing')" :light="true" />
        <div class="auth-story">
            <span class="auth-eyebrow">Tu espacio digital</span>
            <h2>La tranquilidad empieza con una buena llave.</h2>
            <p>Guarda y organiza tus credenciales en un espacio privado, diseñado alrededor de tu seguridad.</p>
        </div>
        <div class="auth-aside-foot">SecureVault · Privacidad bajo tu control</div>
    </aside>
    <main class="auth-main">
        <div class="auth-mobile-brand"><x-brand-logo :href="route('landing')" /></div>
        <section class="auth-card">@yield('auth-content')</section>
        <p class="auth-back"><a href="{{ route('landing') }}">Volver a SecureVault</a></p>
    </main>
</div>
</body>
</html>
