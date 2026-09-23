<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
$v = time();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Testador & Simulador de Favicon // 4U.IA.BR</title>
  <meta name="description" content="Simule e teste favicons em tempo real em abas do Google Chrome (Dark/Light), busca do Google, celular e resoluções nativas. 100% no navegador.">

  <!-- Favicons iniciais -->
  <link id="dynamic-favicon" rel="icon" type="image/png" sizes="32x32" href="fav.png?v=<?= $v ?>">
  <link id="dynamic-favicon-16" rel="icon" type="image/png" sizes="16x16" href="fav.png?v=<?= $v ?>">
  <link id="apple-touch-icon" rel="apple-touch-icon" sizes="180x180" href="fav.png?v=<?= $v ?>">

  <!-- Google Fonts & Font Awesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- JSZip para exportação client-side instantânea -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

  <style>
    :root {
      --bg: #090d16;
      --card-bg: #111827;
      --card-border: rgba(255, 255, 255, 0.08);
      --accent: #38bdf8;
      --accent-glow: rgba(56, 189, 248, 0.25);
      --text: #f1f5f9;
      --text-muted: #94a3b8;
      --radius: 16px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      background-image: 
        radial-gradient(ellipse at 50% 0%, rgba(56, 189, 248, 0.14), transparent 50%),
        radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.08), transparent 40%);
    }

    /* Top Nav Bar */
    .top-navbar {
      width: 100%;
      background: rgba(15, 23, 42, 0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--card-border);
      padding: 0.85rem 1.5rem;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .nav-container {
      max-width: 1120px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .brand-group {
      display: flex;
      align-items: center;
      gap: 0.85rem;
      text-decoration: none;
    }

    .brand-logo-img {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      object-fit: contain;
    }

    .brand-name {
      font-size: 1.05rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      color: #fff;
    }

    .brand-name span {
      color: var(--accent);
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .nav-btn {
      font-size: 0.82rem;
      font-weight: 600;
      padding: 0.45rem 0.85rem;
      border-radius: 8px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      transition: all 0.2s ease;
    }

    .nav-btn-muted {
      color: var(--text-muted);
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(255, 255, 255, 0.04);
    }

    .nav-btn-muted:hover {
      color: #fff;
      border-color: rgba(255, 255, 255, 0.25);
      background: rgba(255, 255, 255, 0.08);
    }

    .nav-btn-donate {
      background: rgba(234, 179, 8, 0.15);
      color: #fef08a;
      border: 1px solid rgba(234, 179, 8, 0.35);
    }

    .nav-btn-donate:hover {
      background: #eab308;
      color: #000;
      box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3);
    }

    .container {
      max-width: 1120px;
      width: 100%;
      margin: 0 auto;
      padding: 2rem 1.5rem;
      flex: 1;
    }

    /* Status Banner for Protocol */
    .protocol-banner {
      border-radius: 12px;
      padding: 0.9rem 1.25rem;
      margin-bottom: 2rem;
      display: none;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .protocol-banner.warning {
      background: rgba(234, 179, 8, 0.12);
      border: 1px solid rgba(234, 179, 8, 0.35);
      color: #fef08a;
    }

    .protocol-banner.success {
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.35);
      color: #bbf7d0;
    }

    .banner-text {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.92rem;
      line-height: 1.5;
    }

    .banner-text i {
      font-size: 1.25rem;
    }

    .banner-btn {
      background: #eab308;
      color: #000;
      font-weight: 700;
      font-size: 0.85rem;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: opacity 0.2s;
    }

    .banner-btn:hover {
      opacity: 0.9;
    }

    header {
      text-align: center;
      margin-bottom: 2.25rem;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.35rem 0.85rem;
      background: rgba(56, 189, 248, 0.1);
      border: 1px solid rgba(56, 189, 248, 0.25);
      border-radius: 9999px;
      color: var(--accent);
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 0.85rem;
    }

    h1 {
      font-size: 2.4rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      margin-bottom: 0.5rem;
      background: linear-gradient(135deg, #ffffff 40%, #94a3b8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .subtitle {
      color: var(--text-muted);
      font-size: 1.05rem;
      max-width: 680px;
      margin: 0 auto;
      line-height: 1.55;
    }

    /* Top Control Bar */
    .controls-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      padding: 1.25rem 1.5rem;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.25rem;
      margin-bottom: 1.75rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .source-info {
      display: flex;
      align-items: center;
      gap: 1.15rem;
    }

    .current-img-preview {
      width: 58px;
      height: 58px;
      border-radius: 12px;
      border: 1px solid var(--card-border);
      background: #1e293b;
      padding: 4px;
      object-fit: contain;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }

    .source-text h3 {
      font-size: 1.08rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .source-text p {
      color: var(--text-muted);
      font-size: 0.85rem;
      font-family: 'JetBrains Mono', monospace;
      margin-top: 2px;
    }

    .actions-group {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.65rem 1.15rem;
      border-radius: 10px;
      font-size: 0.88rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.2s ease;
      text-decoration: none;
    }

    .btn-primary {
      background: var(--accent);
      color: #04101e;
      box-shadow: 0 4px 14px var(--accent-glow);
    }

    .btn-primary:hover {
      background: #7dd3fc;
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.06);
      color: var(--text);
      border: 1px solid var(--card-border);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.12);
      border-color: rgba(255, 255, 255, 0.2);
    }

    .btn-success {
      background: #10b981;
      color: #042f1a;
      box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
    }

    .btn-success:hover {
      background: #34d399;
      transform: translateY(-1px);
    }

    /* Drag & Drop Upload Zone */
    .dropzone {
      border: 2px dashed rgba(56, 189, 248, 0.35);
      background: rgba(56, 189, 248, 0.03);
      border-radius: var(--radius);
      padding: 1.5rem;
      text-align: center;
      margin-bottom: 2.25rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .dropzone:hover, .dropzone.dragover {
      border-color: var(--accent);
      background: rgba(56, 189, 248, 0.08);
      transform: translateY(-1px);
    }

    .dropzone i {
      font-size: 2rem;
      color: var(--accent);
      margin-bottom: 0.5rem;
    }

    .dropzone p {
      font-size: 0.95rem;
      color: var(--text-muted);
    }

    .dropzone span {
      color: var(--accent);
      font-weight: 600;
      text-decoration: underline;
    }

    .dropzone-tip {
      font-size: 0.78rem !important;
      color: rgba(255, 255, 255, 0.4) !important;
      margin-top: 0.4rem;
    }

    /* Grid Layout for Previews */
    .section-title {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 0.65rem;
    }

    .section-title i {
      color: var(--accent);
    }

    .mockup-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .mockup-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    }

    .mockup-header {
      padding: 0.75rem 1.25rem;
      background: rgba(255, 255, 255, 0.03);
      border-bottom: 1px solid var(--card-border);
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .mockup-content {
      padding: 1.25rem;
    }

    /* Browser Mockup Dark */
    .chrome-window-dark {
      background: #202124;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }

    .chrome-tabbar-dark {
      display: flex;
      align-items: flex-end;
      background: #1f2023;
      padding: 8px 8px 0 8px;
      gap: 4px;
    }

    .chrome-tab-dark {
      background: #35363a;
      border-radius: 8px 8px 0 0;
      padding: 7px 14px;
      display: flex;
      align-items: center;
      gap: 9px;
      max-width: 210px;
      font-size: 12px;
      color: #e8eaed;
      white-space: nowrap;
      overflow: hidden;
    }

    .chrome-tab-dark img {
      width: 16px;
      height: 16px;
      flex-shrink: 0;
      object-fit: contain;
    }

    .chrome-tab-dark span {
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .chrome-tab-dark .close-btn {
      margin-left: auto;
      font-size: 14px;
      color: #9aa0a6;
    }

    .chrome-inactive-dark {
      padding: 7px 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: #9aa0a6;
    }

    .chrome-addressbar-dark {
      background: #292a2d;
      padding: 8px 14px;
      font-size: 12px;
      color: #9aa0a6;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid #3c4043;
    }

    .chrome-body-dark {
      padding: 20px;
      font-size: 12px;
      color: #5f6368;
      text-align: center;
      background: #202124;
    }

    /* Browser Mockup Light */
    .chrome-window-light {
      background: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }

    .chrome-tabbar-light {
      display: flex;
      align-items: flex-end;
      background: #dee1e6;
      padding: 8px 8px 0 8px;
      gap: 4px;
    }

    .chrome-tab-light {
      background: #ffffff;
      border-radius: 8px 8px 0 0;
      padding: 7px 14px;
      display: flex;
      align-items: center;
      gap: 9px;
      max-width: 210px;
      font-size: 12px;
      color: #3c4043;
      white-space: nowrap;
      overflow: hidden;
    }

    .chrome-tab-light img {
      width: 16px;
      height: 16px;
      flex-shrink: 0;
      object-fit: contain;
    }

    .chrome-addressbar-light {
      background: #f1f3f4;
      padding: 8px 14px;
      font-size: 12px;
      color: #5f6368;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid #e8eaed;
    }

    .chrome-body-light {
      padding: 20px;
      font-size: 12px;
      color: #9aa0a6;
      text-align: center;
      background: #ffffff;
    }

    /* Google Search Snippet Mockup */
    .google-snippet {
      background: #202124;
      padding: 1.25rem;
      border-radius: 10px;
      font-family: Arial, sans-serif;
    }

    .google-site-line {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 8px;
    }

    .google-fav-circle {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #303134;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .google-fav-circle img {
      width: 18px;
      height: 18px;
      object-fit: contain;
    }

    .google-site-name {
      color: #dadce0;
      font-size: 14px;
      font-weight: 500;
    }

    .google-site-url {
      color: #bdc1c6;
      font-size: 12px;
    }

    .google-title {
      color: #8ab4f8;
      font-size: 18px;
      text-decoration: underline;
      margin-bottom: 4px;
      cursor: pointer;
    }

    .google-desc {
      color: #bdc1c6;
      font-size: 13px;
      line-height: 1.5;
    }

    /* Smartphone Icon Mockup */
    .mobile-screen {
      background: linear-gradient(180deg, #1e1b4b, #0f172a);
      border-radius: 20px;
      padding: 24px 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 24px;
      box-shadow: inset 0 0 15px rgba(0,0,0,0.5);
    }

    .mobile-app-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .mobile-app-icon {
      width: 60px;
      height: 60px;
      border-radius: 14px;
      background: #000000;
      box-shadow: 0 6px 14px rgba(0,0,0,0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.1);
    }

    .mobile-app-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .mobile-app-label {
      font-size: 11px;
      color: #ffffff;
      font-weight: 500;
      text-shadow: 0 1px 3px rgba(0,0,0,0.8);
    }

    /* Real Resolution Sizes Grid */
    .sizes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2.5rem;
    }

    .size-card {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      padding: 1.25rem 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.75rem;
      text-align: center;
      transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .size-card:hover {
      transform: translateY(-2px);
      border-color: rgba(56, 189, 248, 0.35);
    }

    .size-box {
      background: #0b101b;
      border: 1px dashed rgba(255, 255, 255, 0.15);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px;
    }

    .size-label {
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--accent);
      font-family: 'JetBrains Mono', monospace;
    }

    .size-desc {
      font-size: 0.76rem;
      color: var(--text-muted);
      line-height: 1.35;
    }

    .size-dl-btn {
      margin-top: auto;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: var(--text);
      font-size: 0.75rem;
      padding: 0.35rem 0.65rem;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: all 0.2s ease;
    }

    .size-dl-btn:hover {
      background: var(--accent);
      color: #000;
      border-color: var(--accent);
    }

    /* Export Box */
    .export-box {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      padding: 1.75rem;
      margin-bottom: 2rem;
    }

    .export-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 1.25rem;
    }

    .export-header h3 {
      font-size: 1.15rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .export-actions {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    /* Code & Tips Box */
    .info-box {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      border-radius: var(--radius);
      padding: 1.75rem;
      margin-bottom: 2rem;
    }

    .code-block {
      background: #0b1120;
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 10px;
      padding: 1.15rem;
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.85rem;
      color: #38bdf8;
      overflow-x: auto;
      margin-top: 0.75rem;
      line-height: 1.6;
      position: relative;
    }

    .code-copy-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 0.75rem;
      padding: 0.35rem 0.65rem;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: all 0.2s ease;
    }

    .code-copy-btn:hover {
      background: var(--accent);
      color: #000;
    }

    /* Standard 4U Footer */
    .footer-clean {
      position: relative;
      width: 100%;
      z-index: 100;
      padding: 2.5rem 1.5rem;
      background: rgba(11, 15, 25, 0.95);
      backdrop-filter: blur(10px);
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-top: 3rem;
    }

    .footer-brand {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.88rem;
      font-weight: 700;
      color: #cbd5e1;
    }

    .footer-brand i {
      color: var(--accent);
    }

    .footer-links {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: #94a3b8;
      text-decoration: none;
      font-size: 0.82rem;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .footer-links a:hover {
      color: #f1f5f9;
    }

    .footer-links .sep {
      color: rgba(255, 255, 255, 0.15);
      font-size: 0.75rem;
    }

    .footer-links a.donate-link {
      color: #fef08a;
      font-weight: 700;
    }

    .footer-links a.donate-link:hover {
      color: #facc15;
    }

    .footer-copyright {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.35);
      letter-spacing: 0.05em;
    }

    /* Toast Notification */
    #toast {
      position: fixed;
      bottom: 28px;
      right: 28px;
      background: #0284c7;
      color: white;
      padding: 12px 22px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.92rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      gap: 10px;
      z-index: 10000;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>

  <!-- Top Navigation Bar -->
  <nav class="top-navbar">
    <div class="nav-container">
      <a href="https://4u.ia.br" class="brand-group">
        <img src="fav.png?v=<?= $v ?>" class="brand-logo-img" alt="4U">
        <div class="brand-name">4U<span>.IA.BR</span> // <span style="font-weight:600; color:#cbd5e1;">Testador Favicon</span></div>
      </a>
      <div class="nav-actions">
        <a href="https://4u.ia.br/loja/" class="nav-btn nav-btn-muted">
          <i class="fas fa-store"></i> Loja de Apps
        </a>
        <a href="https://4u.ia.br/app/favicon/" class="nav-btn nav-btn-muted" title="Gerador avançado de ZIP com manifest.json">
          <i class="fas fa-cogs"></i> Gerador ZIP & Manifest
        </a>
        <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="nav-btn nav-btn-donate" title="Apoie o Projeto via PayPal">
          <i class="fas fa-coffee"></i> Apoie
        </a>
      </div>
    </div>
  </nav>

  <div class="container">
    
    <!-- Protocol Banner (mostra apenas se executado offline via file://) -->
    <div id="protocol-banner" class="protocol-banner warning">
      <div class="banner-text">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
          <strong id="banner-title">Modo Arquivo Local (file://)</strong><br>
          <span id="banner-desc">Por segurança, navegadores Chromium bloqueiam a troca dinâmica de favicon em arquivos locais. Acesse via servidor web (HTTP/HTTPS) para visualização na aba real.</span>
        </div>
      </div>
      <a id="banner-link" href="https://4u.ia.br/app/testador-favicon/" class="banner-btn">
        <i class="fas fa-globe"></i> Abrir Online na 4U.IA.BR
      </a>
    </div>

    <header>
      <div class="badge">
        <i class="fas fa-magic"></i> Inspetor & Testador em Tempo Real
      </div>
      <h1>Laboratório & Testador de Favicon</h1>
      <p class="subtitle">
        Veja instantaneamente como o seu favicon aparece na aba real do seu navegador, na busca do Google, na tela inicial do celular e em todas as resoluções nativas da web.
      </p>
    </header>

    <!-- Top Controls Card -->
    <div class="controls-card">
      <div class="source-info">
        <img id="current-fav-img" src="fav.png?v=<?= $v ?>" class="current-img-preview" alt="Favicon atual" onerror="handleImgError(this)">
        <div class="source-text">
          <h3 id="image-status-title">Carregando imagem...</h3>
          <p id="image-meta">Arquivo: <strong>fav.png</strong></p>
        </div>
      </div>

      <div class="actions-group">
        <button class="btn btn-primary" onclick="reloadFavicon(true)">
          <i class="fas fa-sync-alt"></i> Recarregar (Forçar Cache)
        </button>
        <button class="btn btn-secondary" onclick="document.getElementById('file-input').click()">
          <i class="fas fa-folder-open"></i> Testar Outra Imagem...
        </button>
        <input type="file" id="file-input" accept="image/*" style="display: none;" onchange="handleFileSelect(event)">
      </div>
    </div>

    <!-- Drag and drop zone -->
    <div class="dropzone" id="dropzone" onclick="document.getElementById('file-input').click()">
      <i class="fas fa-cloud-upload-alt"></i>
      <p>Quer testar qualquer imagem? <span>Clique aqui</span>, <strong>arraste e solte</strong> ou <strong>cole da área de transferência (Ctrl + V)</strong>.</p>
      <p class="dropzone-tip">Suporta PNG, JPG, WebP e SVG transparente ou sólido</p>
    </div>

    <!-- Simulation Mockups -->
    <h2 class="section-title"><i class="fas fa-desktop"></i> Simulação em Navegadores & Sistemas Reais</h2>
    
    <div class="mockup-grid">
      
      <!-- Mockup 1: Google Chrome Dark Mode -->
      <div class="mockup-card">
        <div class="mockup-header">
          <span><i class="fab fa-chrome"></i> Aba no Chrome (Modo Escuro)</span>
          <span style="color: #4ade80;">16 × 16 px</span>
        </div>
        <div class="mockup-content">
          <div class="chrome-window-dark">
            <div class="chrome-tabbar-dark">
              <div class="chrome-tab-dark">
                <img class="preview-target" src="fav.png?v=<?= $v ?>" alt="icon">
                <span>4U.IA.BR // Inovação e IA</span>
                <span class="close-btn">&times;</span>
              </div>
              <div class="chrome-inactive-dark">
                <i class="fas fa-plus" style="font-size: 11px;"></i>
              </div>
            </div>
            <div class="chrome-addressbar-dark">
              <i class="fas fa-lock" style="font-size: 10px; color: #8ab4f8;"></i>
              <span>https://4u.ia.br</span>
            </div>
            <div class="chrome-body-dark">
              Simulação de aba ativa em navegador com tema Dark
            </div>
          </div>
        </div>
      </div>

      <!-- Mockup 2: Google Chrome Light Mode -->
      <div class="mockup-card">
        <div class="mockup-header">
          <span><i class="fab fa-chrome"></i> Aba no Chrome (Modo Claro)</span>
          <span style="color: #4ade80;">16 × 16 px</span>
        </div>
        <div class="mockup-content">
          <div class="chrome-window-light">
            <div class="chrome-tabbar-light">
              <div class="chrome-tab-light">
                <img class="preview-target" src="fav.png?v=<?= $v ?>" alt="icon">
                <span>4U.IA.BR // Inovação e IA</span>
                <span class="close-btn" style="margin-left: auto; color: #5f6368;">&times;</span>
              </div>
              <div style="padding: 7px 10px; color: #5f6368; font-size: 11px;">
                <i class="fas fa-plus"></i>
              </div>
            </div>
            <div class="chrome-addressbar-light">
              <i class="fas fa-lock" style="font-size: 10px; color: #1a73e8;"></i>
              <span>https://4u.ia.br</span>
            </div>
            <div class="chrome-body-light">
              Simulação de aba ativa em navegador com tema Claro
            </div>
          </div>
        </div>
      </div>

      <!-- Mockup 3: Google Search SERP -->
      <div class="mockup-card">
        <div class="mockup-header">
          <span><i class="fab fa-google"></i> Resultado no Google Search</span>
          <span style="color: #4ade80;">18 × 18 px</span>
        </div>
        <div class="mockup-content">
          <div class="google-snippet">
            <div class="google-site-line">
              <div class="google-fav-circle">
                <img class="preview-target" src="fav.png?v=<?= $v ?>" alt="google fav">
              </div>
              <div>
                <div class="google-site-name">4U.IA.BR</div>
                <div class="google-site-url">https://4u.ia.br</div>
              </div>
            </div>
            <div class="google-title">4U.IA.BR // Ecossistema de Aplicações e Inteligência Artificial</div>
            <div class="google-desc">
              Mais de 90 aplicações profissionais rodando 100% no navegador, zero instalação, aceleração e privacidade total.
            </div>
          </div>
        </div>
      </div>

      <!-- Mockup 4: Smartphone Home Screen -->
      <div class="mockup-card">
        <div class="mockup-header">
          <span><i class="fas fa-mobile-alt"></i> Atalho na Tela do Smartphone (PWA)</span>
          <span style="color: #4ade80;">192 × 192 px</span>
        </div>
        <div class="mockup-content">
          <div class="mobile-screen">
            <div class="mobile-app-item">
              <div class="mobile-app-icon">
                <img class="preview-target" src="fav.png?v=<?= $v ?>" alt="mobile icon">
              </div>
              <span class="mobile-app-label">Meu WebApp</span>
            </div>
            <div class="mobile-app-item" style="opacity: 0.45;">
              <div class="mobile-app-icon" style="background: #1e293b; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-camera" style="font-size: 24px; color: #94a3b8;"></i>
              </div>
              <span class="mobile-app-label">Câmera</span>
            </div>
            <div class="mobile-app-item" style="opacity: 0.45;">
              <div class="mobile-app-icon" style="background: #1e293b; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-envelope" style="font-size: 24px; color: #94a3b8;"></i>
              </div>
              <span class="mobile-app-label">E-mail</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Scaled Resolutions Preview -->
    <h2 class="section-title"><i class="fas fa-th-large"></i> Renderização em Resoluções Nativas & Download</h2>
    
    <div class="sizes-grid">
      <div class="size-card">
        <div class="size-box" style="width: 34px; height: 34px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 16px; height: 16px; object-fit: contain;">
        </div>
        <div class="size-label">16 × 16</div>
        <div class="size-desc">Aba padrão Chrome/Edge/Firefox</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(16, 'favicon-16x16.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 48px; height: 48px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 32px; height: 32px; object-fit: contain;">
        </div>
        <div class="size-label">32 × 32</div>
        <div class="size-desc">Telas HiDPI / Barra de Favoritos</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(32, 'favicon-32x32.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 64px; height: 64px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 48px; height: 48px; object-fit: contain;">
        </div>
        <div class="size-label">48 × 48</div>
        <div class="size-desc">Atalhos no Windows / Barra de Tarefas</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(48, 'favicon-48x48.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 80px; height: 80px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 64px; height: 64px; object-fit: contain;">
        </div>
        <div class="size-label">64 × 64</div>
        <div class="size-desc">macOS Dock / Alta Densidade</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(64, 'favicon-64x64.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 104px; height: 104px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 88px; height: 88px; object-fit: contain;">
        </div>
        <div class="size-label">180 × 180</div>
        <div class="size-desc">Apple Touch Icon (iOS / Safari)</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(180, 'apple-touch-icon.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 104px; height: 104px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 88px; height: 88px; object-fit: contain;">
        </div>
        <div class="size-label">192 × 192</div>
        <div class="size-desc">Android PWA / Web App Manifest</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(192, 'icon-192.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>

      <div class="size-card">
        <div class="size-box" style="width: 104px; height: 104px;">
          <img class="preview-target" src="fav.png?v=<?= $v ?>" style="width: 88px; height: 88px; object-fit: contain;">
        </div>
        <div class="size-label">512 × 512</div>
        <div class="size-desc">Splash Screen & PWA Alta Resolução</div>
        <button class="size-dl-btn" onclick="downloadSingleSize(512, 'icon-512.png')">
          <i class="fas fa-download"></i> Baixar PNG
        </button>
      </div>
    </div>

    <!-- Export Full Package Box -->
    <div class="export-box">
      <div class="export-header">
        <div>
          <h3><i class="fas fa-file-archive" style="color: #38bdf8;"></i> Exportação Instantânea dos Assets</h3>
          <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px;">
            Gere todos os tamanhos padrão em um único arquivo compactado diretamente pelo seu navegador.
          </p>
        </div>
        <div class="export-actions">
          <button class="btn btn-success" id="btn-download-zip" onclick="downloadAllZip()">
            <i class="fas fa-file-zipper"></i> Baixar Pacote Completo (.ZIP)
          </button>
          <a href="https://4u.ia.br/app/favicon/" class="btn btn-secondary">
            <i class="fas fa-sliders-h"></i> Mais Opções (Gerador Avançado)
          </a>
        </div>
      </div>
    </div>

    <!-- Instructions & Code Box -->
    <div class="info-box">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom: 0.75rem;">
        <h3 style="font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
          <i class="fas fa-code" style="color: #38bdf8;"></i> Tags HTML para o seu site
        </h3>
        <button class="btn btn-secondary" style="font-size:0.8rem; padding: 0.4rem 0.8rem;" onclick="copyCodeSnippet()">
          <i class="fas fa-copy"></i> Copiar Código
        </button>
      </div>
      <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 0.75rem;">
        Insira estas tags dentro da seção <code>&lt;head&gt;</code> do seu HTML para compatibilidade total em desktops, navegadores modernos e dispositivos Apple:
      </p>
      
      <div class="code-block" id="html-code-block">&lt;link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"&gt;
&lt;link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"&gt;
&lt;link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"&gt;
&lt;link rel="manifest" href="/site.webmanifest"&gt;</div>
    </div>

  </div>

  <!-- Standard 4U Footer -->
  <footer class="footer-clean">
    <div class="footer-brand">
      <i class="fas fa-icons"></i> <span>Testador de Favicon — 4U.IA.BR</span>
    </div>
    <div class="footer-links">
      <a href="privacidade.php">Privacidade</a>
      <span class="sep">•</span>
      <a href="termos.php">Termos de Uso</a>
      <span class="sep">•</span>
      <a href="suporte.php">Suporte & FAQ</a>
      <span class="sep">•</span>
      <a href="https://www.paypal.com/ncp/payment/L7YRCS984T33N" target="_blank" rel="noopener noreferrer" class="donate-link" title="Apoie o Projeto via PayPal">☕ Apoie</a>
      <span class="sep">•</span>
      <a href="https://github.com/4u-Labs" target="_blank" rel="noopener noreferrer" title="4U.IA.BR no GitHub">GitHub</a>
    </div>
    <div class="footer-copyright">
      &copy; <span id="year"><?php echo date('Y'); ?></span> 4U.IA.BR — Todos os direitos reservados.
    </div>
  </footer>

  <div id="toast">
    <i class="fas fa-check-circle"></i> <span id="toast-text">Favicon atualizado com sucesso!</span>
  </div>

  <script>
    // Mantém referência da imagem em memória
    let currentImageElement = null;
    let currentImageSrc = 'fav.png?v=<?= $v ?>';
    let currentFileName = 'fav.png';

    // Banner de protocolo
    function setupProtocolBanner() {
      const banner = document.getElementById('protocol-banner');
      if (window.location.protocol === 'file:') {
        banner.style.display = 'flex';
      } else {
        banner.style.display = 'none';
      }
    }

    // Cria canvas redimensionado de alta fidelidade
    function renderToCanvas(img, width, height) {
      const canvas = document.createElement('canvas');
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';
      ctx.drawImage(img, 0, 0, width, height);
      return canvas;
    }

    // Atualiza o favicon real da aba ativa do navegador
    function setBrowserFavicon(url) {
      document.querySelectorAll("link[rel*='icon']").forEach(el => el.remove());

      const link32 = document.createElement('link');
      link32.rel = 'icon';
      link32.type = 'image/png';
      link32.sizes = '32x32';
      link32.href = url;
      document.head.appendChild(link32);

      const link16 = document.createElement('link');
      link16.rel = 'icon';
      link16.type = 'image/png';
      link16.sizes = '16x16';
      link16.href = url;
      document.head.appendChild(link16);

      const appleLink = document.createElement('link');
      appleLink.rel = 'apple-touch-icon';
      appleLink.sizes = '180x180';
      appleLink.href = url;
      document.head.appendChild(appleLink);
    }

    function showToast(message) {
      const toast = document.getElementById('toast');
      document.getElementById('toast-text').innerText = message;
      toast.style.display = 'flex';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 3500);
    }

    function copyCodeSnippet() {
      const code = document.getElementById('html-code-block').innerText;
      navigator.clipboard.writeText(code).then(() => {
        showToast('Código HTML copiado para a área de transferência!');
      }).catch(() => {
        showToast('Código copiado!');
      });
    }

    function reloadFavicon(notify) {
      const timestamp = new Date().getTime();
      const newUrl = 'fav.png?v=' + timestamp;

      const testImg = new Image();
      testImg.crossOrigin = 'anonymous';
      testImg.onload = function() {
        currentImageElement = this;
        currentImageSrc = newUrl;
        currentFileName = 'fav.png';

        const canvas = renderToCanvas(this, 64, 64);
        setBrowserFavicon(canvas.toDataURL('image/png'));

        // Atualiza todas as previews
        const previews = document.querySelectorAll('.preview-target, #current-fav-img');
        previews.forEach(img => {
          img.src = newUrl;
        });

        checkImageDimensions(this);

        if (notify) {
          showToast('Favicon fav.png recarregado na aba com sucesso!');
        }
      };
      testImg.onerror = function() {
        document.getElementById('image-status-title').innerHTML = 
          `<i class="fas fa-exclamation-triangle" style="color: #f87171;"></i> Imagem padrão não encontrada`;
        document.getElementById('image-meta').innerHTML = 
          `Faça upload de uma imagem para testar.`;
      };
      testImg.src = newUrl;
    }

    function checkImageDimensions(img) {
      const isSquare = (img.naturalWidth === img.naturalHeight);
      document.getElementById('image-status-title').innerHTML = 
        `<i class="fas fa-check-circle" style="color: #4ade80;"></i> Imagem Carregada (${img.naturalWidth} × ${img.naturalHeight} px)`;
      document.getElementById('image-meta').innerHTML = 
        `Proporção: <strong>${isSquare ? '1:1 (Perfeita)' : (img.naturalWidth + ':' + img.naturalHeight + ' — Não quadrada')}</strong> | Arquivo: ${currentFileName}`;
    }

    function applyNewImageSource(dataUrl, fileName, img) {
      currentImageElement = img;
      currentImageSrc = dataUrl;
      currentFileName = fileName;

      const canvas = renderToCanvas(img, 64, 64);
      setBrowserFavicon(canvas.toDataURL('image/png'));

      const previews = document.querySelectorAll('.preview-target, #current-fav-img');
      previews.forEach(pImg => {
        pImg.src = dataUrl;
      });

      checkImageDimensions(img);
      showToast(`Imagem "${fileName}" aplicada no favicon e mockups!`);
    }

    function handleFile(file) {
      if (!file || !file.type.startsWith('image/')) {
        alert('Por favor, selecione um arquivo de imagem válido (PNG, JPG, SVG, WebP).');
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        const dataUrl = e.target.result;
        const img = new Image();
        img.onload = function() {
          applyNewImageSource(dataUrl, file.name, this);
        };
        img.src = dataUrl;
      };
      reader.readAsDataURL(file);
    }

    function handleFileSelect(event) {
      const file = event.target.files[0];
      if (file) handleFile(file);
    }

    // Drag & Drop
    const dropzone = document.getElementById('dropzone');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, preventDefaults, false);
      document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, () => dropzone.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, () => dropzone.classList.remove('dragover'), false);
    });

    dropzone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files && files.length > 0) {
        handleFile(files[0]);
      }
    });

    // Paste da Área de Transferência (Ctrl + V)
    window.addEventListener('paste', (e) => {
      const items = (e.clipboardData || e.originalEvent.clipboardData).items;
      for (const item of items) {
        if (item.type.indexOf('image') === 0) {
          const blob = item.getAsFile();
          handleFile(blob);
          break;
        }
      }
    });

    // Download individual por tamanho
    function downloadSingleSize(size, filename) {
      if (!currentImageElement) {
        alert('Carregando imagem, aguarde um instante...');
        return;
      }
      const canvas = renderToCanvas(currentImageElement, size, size);
      const link = document.createElement('a');
      link.download = filename;
      link.href = canvas.toDataURL('image/png');
      link.click();
      showToast(`Download de ${filename} iniciado!`);
    }

    // Download do pacote ZIP completo com JSZip
    async function downloadAllZip() {
      if (!currentImageElement) {
        alert('Carregando imagem, aguarde um instante...');
        return;
      }

      if (typeof JSZip === 'undefined') {
        alert('Biblioteca de compactação não carregou. Baixe individualmente pelos botões acima.');
        return;
      }

      const btn = document.getElementById('btn-download-zip');
      const originalHtml = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando ZIP...';
      btn.disabled = true;

      try {
        const zip = new JSZip();
        const sizes = [
          { size: 16, name: 'favicon-16x16.png' },
          { size: 32, name: 'favicon-32x32.png' },
          { size: 48, name: 'favicon-48x48.png' },
          { size: 64, name: 'favicon-64x64.png' },
          { size: 180, name: 'apple-touch-icon.png' },
          { size: 192, name: 'icon-192.png' },
          { size: 512, name: 'icon-512.png' }
        ];

        for (const item of sizes) {
          const canvas = renderToCanvas(currentImageElement, item.size, item.size);
          const dataUrl = canvas.toDataURL('image/png');
          const base64Data = dataUrl.replace(/^data:image\/png;base64,/, '');
          zip.file(item.name, base64Data, { base64: true });
        }

        // Adiciona um site.webmanifest básico
        const manifestContent = JSON.stringify({
          name: "Meu WebApp",
          short_name: "WebApp",
          icons: [
            { src: "/icon-192.png", sizes: "192x192", type: "image/png" },
            { src: "/icon-512.png", sizes: "512x512", type: "image/png" }
          ],
          theme_color: "#0f172a",
          background_color: "#0f172a",
          display: "standalone"
        }, null, 2);
        zip.file("site.webmanifest", manifestContent);

        // Gera o arquivo ZIP
        const blob = await zip.generateAsync({ type: "blob" });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'favicon-assets-4u.zip';
        link.click();

        showToast('Pacote ZIP completo baixado com sucesso!');
      } catch (err) {
        console.error(err);
        alert('Erro ao gerar arquivo ZIP: ' + err.message);
      } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
      }
    }

    function handleImgError(img) {
      img.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"><rect width="64" height="64" fill="%23334155"/><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="%2394a3b8" font-size="12">Sem Img</text></svg>';
    }

    // Inicialização ao carregar
    window.addEventListener('DOMContentLoaded', () => {
      setupProtocolBanner();
      reloadFavicon(false);
      const y = new Date().getFullYear();
      if (document.getElementById('year')) document.getElementById('year').textContent = y;
    });
  </script>
</body>
</html>
