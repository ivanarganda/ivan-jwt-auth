<style>
:root {
    --bg: #0a0f1c;
    --bg-soft: #101827;
    --panel: #111827;
    --panel-2: #1e293b;
    --panel-3: #0f172a;
    --border: #2b3950;
    --border-soft: rgba(51, 65, 85, 0.7);
    --text: #e5e7eb;
    --muted: #94a3b8;
    --primary: #4f46e5;
    --primary-hover: #4338ca;
    --success: #22c55e;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #38bdf8;
    --radius: 16px;
    --radius-lg: 22px;
    --shadow: 0 14px 35px rgba(0,0,0,.28);
}

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    margin: 0;
    font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background:
        radial-gradient(circle at top left, rgba(79,70,229,.18), transparent 25%),
        radial-gradient(circle at bottom right, rgba(56,189,248,.08), transparent 25%),
        var(--bg);
    color: var(--text);
}

a {
    text-decoration: none;
    color: inherit;
}

code {
    font-family: Consolas, monospace;
}

.layout {
    display: grid;
    grid-template-columns: 290px 1fr;
    min-height: 100vh;
}

.sidebar {
    position: sticky;
    top: 0;
    height: 100vh;
    padding: 22px 18px;
    background: rgba(8, 13, 25, 0.92);
    border-right: 1px solid var(--border);
    backdrop-filter: blur(12px);
    overflow-y: auto;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
}

.brand-logo {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, var(--primary), var(--info));
    color: white;
    font-weight: bold;
    box-shadow: var(--shadow);
}

.brand-text h2 {
    margin: 0;
    font-size: 18px;
}

.brand-text p {
    margin: 2px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.sidebar-section {
    margin-bottom: 22px;
}

.sidebar-title {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #64748b;
    margin-bottom: 10px;
}

.sidebar-link {
    display: block;
    padding: 10px 12px;
    border-radius: 10px;
    color: var(--muted);
    margin-bottom: 6px;
    transition: .2s;
}

.sidebar-link:hover,
.sidebar-link.active {
    background: rgba(79,70,229,.14);
    color: white;
}

.sidebar-token-box {
    background: linear-gradient(180deg, rgba(30,41,59,.95), rgba(15,23,42,.96));
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 14px;
}

.sidebar-token-box p {
    margin: 0 0 10px;
    font-size: 13px;
    color: var(--muted);
}

.small-btn {
    width: 100%;
    border: 1px solid var(--border);
    background: transparent;
    color: var(--text);
    padding: 10px 12px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: .2s;
}

.small-btn:hover {
    background: rgba(255,255,255,.03);
}

.content {
    padding: 28px;
}

.hero {
    background: linear-gradient(180deg, rgba(30,41,59,.9), rgba(15,23,42,.96));
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 30px;
    box-shadow: var(--shadow);
    margin-bottom: 24px;
}

.eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(79,70,229,.12);
    border: 1px solid rgba(79,70,229,.35);
    color: #c7d2fe;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    margin-bottom: 14px;
}

h1, h2, h3, h4 {
    margin-top: 0;
    color: white;
}

h1 {
    font-size: clamp(30px, 4vw, 48px);
    margin-bottom: 12px;
}

p {
    color: var(--muted);
    line-height: 1.6;
}

.hero-actions,
.action-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.btn {
    border: none;
    border-radius: 10px;
    padding: 12px 16px;
    font-weight: bold;
    cursor: pointer;
    transition: .2s;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-hover);
}

.btn-secondary {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--text);
}

.btn-secondary:hover {
    background: rgba(255,255,255,.03);
}

.btn-small {
    padding: 8px 12px;
    font-size: 13px;
}

.grid {
    display: grid;
    gap: 20px;
}

.grid-2 {
    grid-template-columns: repeat(2, 1fr);
}

.grid-3 {
    grid-template-columns: repeat(3, 1fr);
}

.card {
    background: linear-gradient(180deg, rgba(30,41,59,.92), rgba(15,23,42,.98));
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    box-shadow: var(--shadow);
}

.mini-stat {
    font-size: 28px;
    font-weight: bold;
    color: white;
    margin-bottom: 8px;
}

.section {
    margin-bottom: 24px;
}

.section-head {
    margin-bottom: 14px;
}

.section-head p {
    margin: 0;
}

.base-url {
    font-family: Consolas, monospace;
    background: #020617;
    border: 1px solid #1e293b;
    border-radius: 12px;
    padding: 14px;
    word-break: break-all;
    color: #dbeafe;
}

.endpoint-shell {
    padding: 0;
    overflow: hidden;
}

.endpoint-top {
    padding: 20px;
    border-bottom: 1px solid var(--border-soft);
}

.endpoint-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.endpoint-main {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.method {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 72px;
    padding: 7px 10px;
    border-radius: 999px;
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.method.get {
    background: var(--success);
}

.method.post {
    background: var(--primary);
}

.endpoint-url {
    font-family: Consolas, monospace;
    color: #cbd5e1;
    word-break: break-all;
}

.endpoint-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 16px 20px 0;
}

.tab-btn {
    border: 1px solid var(--border);
    background: transparent;
    color: var(--muted);
    padding: 9px 12px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: .2s;
}

.tab-btn.active {
    background: rgba(79,70,229,.14);
    color: white;
    border-color: rgba(79,70,229,.35);
}

.endpoint-tab-content {
    display: none;
    padding: 20px;
}

.endpoint-tab-content.active {
    display: block;
}

.tag-row,
.status-codes {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    color: var(--muted);
    border: 1px solid var(--border);
    background: rgba(255,255,255,.02);
}

.status-code {
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: bold;
}

.s200 {
    background: rgba(34,197,94,.12);
    color: #bbf7d0;
    border: 1px solid rgba(34,197,94,.35);
}

.s400, .s401, .s500 {
    background: rgba(239,68,68,.12);
    color: #fecaca;
    border: 1px solid rgba(239,68,68,.35);
}

.s422, .s409 {
    background: rgba(245,158,11,.12);
    color: #fde68a;
    border: 1px solid rgba(245,158,11,.35);
}

.s201 {
    background: rgba(34,197,94,.12);
    color: #bbf7d0;
    border: 1px solid rgba(34,197,94,.35);
}

.notice {
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 14px;
    line-height: 1.55;
    border: 1px solid var(--border);
}

.notice-info {
    background: rgba(56,189,248,.08);
    border-color: rgba(56,189,248,.35);
    color: #bae6fd;
}

.notice-warn {
    background: rgba(245,158,11,.1);
    border-color: rgba(245,158,11,.4);
    color: #fde68a;
}

.notice-muted {
    background: rgba(255,255,255,.03);
    color: var(--muted);
}

.notice strong {
    color: white;
}

.tag.badge-soon {
    background: rgba(245,158,11,.15);
    border-color: rgba(245,158,11,.45);
    color: #fde68a;
}

.api-status-card {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(255,255,255,.02);
    margin-bottom: 8px;
}

.api-status-card strong {
    display: block;
    font-size: 13px;
    color: white;
}

.api-status-card p {
    margin: 2px 0 0;
    font-size: 12px;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-top: 4px;
    flex-shrink: 0;
}

.status-dot.ok {
    background: var(--success);
    box-shadow: 0 0 8px rgba(34,197,94,.6);
}

.status-dot.pending {
    background: var(--warning);
    box-shadow: 0 0 8px rgba(245,158,11,.5);
}

.sidebar-toggle {
    display: none;
    position: fixed;
    top: 14px;
    left: 14px;
    z-index: 1001;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(15,23,42,.95);
    color: white;
    font-size: 20px;
    cursor: pointer;
    box-shadow: var(--shadow);
}

.sidebar-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 999;
}

.sidebar-backdrop.visible {
    display: block;
}

.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1100;
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: min(360px, calc(100vw - 40px));
}

.toast {
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(15,23,42,.96);
    color: var(--text);
    font-size: 14px;
    box-shadow: var(--shadow);
    animation: toast-in .25s ease;
}

.toast.success {
    border-color: rgba(34,197,94,.45);
}

.toast.error {
    border-color: rgba(239,68,68,.45);
    color: #fecaca;
}

@keyframes toast-in {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.try-response {
    max-height: 420px;
    overflow: auto;
}

.pill.warn {
    color: #fde68a;
    border-color: rgba(245,158,11,.45);
    background: rgba(245,158,11,.1);
}

.history-item {
    cursor: pointer;
    transition: border-color .2s, background .2s;
}

.history-item:hover {
    border-color: rgba(79,70,229,.45);
    background: rgba(79,70,229,.06);
}

.table-wrap {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: 12px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 520px;
    background: rgba(2,6,23,.35);
}

th, td {
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border-soft);
    font-size: 14px;
}

th {
    color: white;
    background: rgba(255,255,255,.02);
}

td {
    color: var(--muted);
}

pre {
    margin: 0;
    background: #020617;
    border: 1px solid #1e293b;
    color: #dbeafe;
    padding: 14px;
    border-radius: 12px;
    overflow: auto;
    white-space: pre-wrap;
    word-break: break-word;
    font-family: Consolas, monospace;
    font-size: 14px;
}

.try-layout {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    gap: 18px;
}

.field {
    margin-bottom: 16px;
}

label {
    display: inline-block;
    margin-bottom: 8px;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

input, textarea, select {
    width: 100%;
    background: #0b1220;
    color: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14px;
    outline: none;
    transition: .2s;
}

input:focus, textarea:focus, select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.15);
}

textarea {
    min-height: 180px;
    resize: vertical;
    font-family: Consolas, monospace;
}

.response-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.pill {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    border: 1px solid var(--border);
    color: var(--muted);
    background: rgba(255,255,255,.02);
    font-size: 12px;
}

.pill.ok {
    color: #bbf7d0;
    border-color: rgba(34,197,94,.35);
    background: rgba(34,197,94,.08);
}

.pill.error {
    color: #fecaca;
    border-color: rgba(239,68,68,.35);
    background: rgba(239,68,68,.08);
}

.token-box {
    font-family: Consolas, monospace;
    font-size: 13px;
    background: #020617;
    border: 1px solid #1e293b;
    border-radius: 12px;
    padding: 12px;
    color: #dbeafe;
    word-break: break-all;
    min-height: 56px;
}

.history-list {
    display: grid;
    gap: 12px;
}

.history-item {
    background: rgba(2,6,23,.45);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px;
}

.history-item-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.history-item-url {
    font-family: Consolas, monospace;
    color: #cbd5e1;
    font-size: 13px;
    word-break: break-all;
}

.flow {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.flow-item .step {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, var(--primary), var(--info));
    font-weight: bold;
    margin-bottom: 12px;
}

.muted {
    color: var(--muted);
}

.footer {
    text-align: center;
    padding: 20px 0 10px;
    color: var(--muted);
}

@media (max-width: 1150px) {
    .sidebar-toggle {
        display: grid;
        place-items: center;
    }

    .content {
        padding-top: 64px;
    }

    .layout {
        grid-template-columns: 1fr;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        width: min(290px, 88vw);
        height: 100vh;
        transform: translateX(-105%);
        transition: transform .25s ease;
        border-right: 1px solid var(--border);
        border-bottom: none;
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .grid-2,
    .grid-3,
    .try-layout,
    .flow {
        grid-template-columns: 1fr;
    }
}
</style>