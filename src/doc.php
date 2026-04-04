<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = rtrim($protocol . '://' . $_SERVER['HTTP_HOST'] . $basePath, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ivan/jwt-auth — Advanced API Docs</title>
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
            font-family: Arial, sans-serif;
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

        .s422 {
            background: rgba(245,158,11,.12);
            color: #fde68a;
            border: 1px solid rgba(245,158,11,.35);
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
            .layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .grid-2,
            .grid-3,
            .try-layout,
            .flow {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">JWT</div>
            <div class="brand-text">
                <h2>ivan/jwt-auth</h2>
                <p>Advanced API Docs</p>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Documentation</div>
            <a class="sidebar-link active" href="#overview">Overview</a>
            <a class="sidebar-link" href="#summary">Summary</a>
            <a class="sidebar-link" href="#endpoint-show">GET /show</a>
            <a class="sidebar-link" href="#endpoint-register">POST /register</a>
            <a class="sidebar-link" href="#endpoint-login">POST /login</a>
            <a class="sidebar-link" href="#auth-flow">Auth Flow</a>
            <a class="sidebar-link" href="#request-history">Request History</a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Stored Token</div>
            <div class="sidebar-token-box">
                <p id="sidebarTokenState">No JWT token saved yet.</p>
                <button class="small-btn" id="clearStoredTokenBtn">Clear Saved Token</button>
            </div>
        </div>
    </aside>

    <main class="content">
        <section class="hero section" id="overview">
            <div class="eyebrow">Reusable PHP JWT Authentication Base</div>
            <h1>ivan/jwt-auth</h1>
            <p>
                A professional authentication starter for PHP projects with JWT-ready architecture,
                browser-based interactive documentation, request playgrounds, local token storage,
                and reusable endpoint documentation.
            </p>
            <div class="hero-actions">
                <a href="#endpoint-login" class="btn btn-primary">Go to Login</a>
                <a href="#request-history" class="btn btn-secondary">View Request History</a>
            </div>
        </section>

        <section class="section" id="summary">
            <div class="section-head">
                <h2>Project Summary</h2>
                <p>Built to eliminate repetitive authentication setup and create a reusable API foundation.</p>
            </div>

            <div class="grid grid-3">
                <div class="card">
                    <div class="mini-stat">JWT</div>
                    <p>Ready to evolve into protected routes with Authorization Bearer token flows.</p>
                </div>
                <div class="card">
                    <div class="mini-stat">3</div>
                    <p>Core endpoints already documented with detailed request and response sections.</p>
                </div>
                <div class="card">
                    <div class="mini-stat">Try It</div>
                    <p>Interactive testing built directly into the documentation without external tools.</p>
                </div>
            </div>

            <div class="grid grid-2" style="margin-top:20px;">
                <div class="card">
                    <h3>Purpose</h3>
                    <p>
                        This base is designed to become the reusable authentication foundation for future PHP APIs.
                        Instead of rebuilding register, login, validation and token handling every time,
                        you start from a clear and extendable base.
                    </p>
                </div>
                <div class="card">
                    <h3>Base URL</h3>
                    <div class="base-url" id="baseUrlText"><?php echo htmlspecialchars($baseUrl); ?></div>
                    <div class="action-row" style="margin-top:12px;">
                        <button class="btn btn-secondary btn-small" id="copyBaseUrlBtn">Copy Base URL</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-show">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method get">GET</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/show</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/show">Copy Endpoint</button>
                    </div>
                    <h2>Show API Status</h2>
                    <p>Quick health-check endpoint used to verify that the API is mounted and responding correctly.</p>
                </div>

                <div class="endpoint-tabs">
                    <button class="tab-btn active" data-target="show-overview">Overview</button>
                    <button class="tab-btn" data-target="show-request">Request</button>
                    <button class="tab-btn" data-target="show-response">Response</button>
                    <button class="tab-btn" data-target="show-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="show-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">No Body</span>
                        <span class="tag">Health Check</span>
                    </div>
                    <p style="margin-top:16px;">
                        Useful during development to make sure the server is running and the router is working.
                    </p>
                </div>

                <div class="endpoint-tab-content" id="show-request">
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>No</td>
                                    <td>string</td>
                                    <td>Optional for GET requests.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="show-response">
                    <h4>Example Response</h4>
<pre>{
  "status": "success",
  "message": "API running"
}</pre>
                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s200">200 OK</span>
                        <span class="status-code s500">500 Server Error</span>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="show-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/show" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="show" placeholder="Bearer token if needed">
                            </div>
                            <div class="action-row">
                                <button class="btn btn-primary try-btn" data-endpoint-key="show">Send Request</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: GET</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-register">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method post">POST</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/register</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/register">Copy Endpoint</button>
                    </div>
                    <h2>Register User</h2>
                    <p>Create a new user account using basic identity and authentication fields.</p>
                </div>

                <div class="endpoint-tabs">
                    <button class="tab-btn active" data-target="register-overview">Overview</button>
                    <button class="tab-btn" data-target="register-request">Request</button>
                    <button class="tab-btn" data-target="register-response">Response</button>
                    <button class="tab-btn" data-target="register-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="register-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">JSON Body</span>
                        <span class="tag">User Creation</span>
                    </div>
                    <p style="margin-top:16px;">
                        This endpoint should validate the incoming data and create a user in the database.
                    </p>
                </div>

                <div class="endpoint-tab-content" id="register-request">
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Must be application/json.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Body Parameters</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>name</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User display name.</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Unique user email.</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User password.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Example Body</h4>
<pre>{
  "name": "Ivan",
  "email": "ivan@email.com",
  "password": "123456"
}</pre>
                </div>

                <div class="endpoint-tab-content" id="register-response">
                    <h4>Success Response</h4>
<pre>{
  "status": "success",
  "message": "User registered successfully"
}</pre>

                    <h4 style="margin-top:18px;">Error Response</h4>
<pre>{
  "status": "error",
  "message": "Email already exists"
}</pre>

                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s200">200 OK</span>
                        <span class="status-code s422">422 Validation Error</span>
                        <span class="status-code s400">400 Bad Request</span>
                        <span class="status-code s500">500 Server Error</span>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="register-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/register" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="register" placeholder="Usually not needed for register">
                            </div>
                            <div class="field">
                                <label>JSON Body</label>
                                <textarea class="try-body" data-endpoint-key="register">{
  "name": "Ivan",
  "email": "ivan@email.com",
  "password": "123456"
}</textarea>
                            </div>
                            <div class="action-row">
                                <button class="btn btn-primary try-btn" data-endpoint-key="register">Send Request</button>
                                <button class="btn btn-secondary btn-small format-btn" data-endpoint-key="register">Format JSON</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: POST</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-login">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method post">POST</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/login</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/login">Copy Endpoint</button>
                    </div>
                    <h2>Login User</h2>
                    <p>Authenticate a user and return a JWT token to be used in future protected requests.</p>
                </div>

                <div class="endpoint-tabs">
                    <button class="tab-btn active" data-target="login-overview">Overview</button>
                    <button class="tab-btn" data-target="login-request">Request</button>
                    <button class="tab-btn" data-target="login-response">Response</button>
                    <button class="tab-btn" data-target="login-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="login-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">JSON Body</span>
                        <span class="tag">JWT Token</span>
                    </div>
                    <p style="margin-top:16px;">
                        If login is successful and the backend returns a token property, the page stores that JWT in localStorage automatically.
                    </p>
                </div>

                <div class="endpoint-tab-content" id="login-request">
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Must be application/json.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Body Parameters</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>email</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User email.</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User password.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Example Body</h4>
<pre>{
  "email": "ivan@email.com",
  "password": "123456"
}</pre>
                </div>

                <div class="endpoint-tab-content" id="login-response">
                    <h4>Success Response</h4>
<pre>{
  "status": "success",
  "message": "Login successful",
  "token": "your_jwt_token_here"
}</pre>

                    <h4 style="margin-top:18px;">Error Response</h4>
<pre>{
  "status": "error",
  "message": "Invalid credentials"
}</pre>

                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s200">200 OK</span>
                        <span class="status-code s401">401 Unauthorized</span>
                        <span class="status-code s422">422 Validation Error</span>
                        <span class="status-code s500">500 Server Error</span>
                    </div>

                    <h4 style="margin-top:18px;">Stored JWT</h4>
                    <div class="token-box" id="storedTokenBox">No token stored yet.</div>
                    <div class="action-row" style="margin-top:12px;">
                        <button class="btn btn-secondary btn-small" id="copyStoredTokenBtn">Copy Stored Token</button>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="login-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/login" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="login" placeholder="Usually not needed for login">
                            </div>
                            <div class="field">
                                <label>JSON Body</label>
                                <textarea class="try-body" data-endpoint-key="login">{
  "email": "ivan@email.com",
  "password": "123456"
}</textarea>
                            </div>
                            <div class="action-row">
                                <button class="btn btn-primary try-btn" data-endpoint-key="login">Send Request</button>
                                <button class="btn btn-secondary btn-small format-btn" data-endpoint-key="login">Format JSON</button>
                                <button class="btn btn-secondary btn-small" id="useStoredTokenBtn">Use Stored Token</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: POST</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="auth-flow">
            <div class="section-head">
                <h2>Authentication Flow</h2>
                <p>Expected lifecycle of a simple authentication system using this starter base.</p>
            </div>

            <div class="flow">
                <div class="card flow-item">
                    <div class="step">1</div>
                    <h4>Register</h4>
                    <p>Client submits user data to create the account.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">2</div>
                    <h4>Validate & Store</h4>
                    <p>Backend validates the payload and stores the user securely.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">3</div>
                    <h4>Login</h4>
                    <p>User authenticates with email and password.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">4</div>
                    <h4>Receive JWT</h4>
                    <p>The token is returned and used for protected requests.</p>
                </div>
            </div>
        </section>

        <section class="section" id="request-history">
            <div class="section-head">
                <h2>Request History</h2>
                <p>Last requests sent from this page, stored locally in the browser.</p>
            </div>

            <div class="card">
                <div class="action-row" style="margin-bottom:14px;">
                    <button class="btn btn-secondary btn-small" id="clearHistoryBtn">Clear History</button>
                </div>
                <div class="history-list" id="historyList">
                    <p class="muted">No requests stored yet.</p>
                </div>
            </div>
        </section>

        <div class="footer">
            <p>ivan/jwt-auth — Advanced JWT Authentication API Documentation</p>
        </div>
    </main>
</div>

<script>
    const BASE_URL = <?php echo json_encode($baseUrl); ?>;
    const TOKEN_STORAGE_KEY = 'ivan_jwt_auth_token';
    const HISTORY_STORAGE_KEY = 'ivan_jwt_auth_history';

    const endpointMap = {
        show: { method: 'GET', path: '/show' },
        register: { method: 'POST', path: '/register' },
        login: { method: 'POST', path: '/login' }
    };

    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const sections = document.querySelectorAll('main .section[id]');

    function initScrollSpy() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const currentId = entry.target.id;
                    sidebarLinks.forEach(link => {
                        link.classList.toggle('active', link.getAttribute('href') === '#' + currentId);
                    });
                }
            });
        }, {
            threshold: 0.35
        });

        sections.forEach(section => observer.observe(section));
    }

    function initTabs() {
        document.querySelectorAll('.endpoint-shell').forEach(shell => {
            const buttons = shell.querySelectorAll('.tab-btn');
            const contents = shell.querySelectorAll('.endpoint-tab-content');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.dataset.target;

                    buttons.forEach(btn => btn.classList.remove('active'));
                    contents.forEach(content => content.classList.remove('active'));

                    button.classList.add('active');
                    shell.querySelector('#' + targetId).classList.add('active');
                });
            });
        });
    }

    function copyText(text, button = null, temporaryText = 'Copied') {
        navigator.clipboard.writeText(text).then(() => {
            if (button) {
                const original = button.textContent;
                button.textContent = temporaryText;
                setTimeout(() => {
                    button.textContent = original;
                }, 1400);
            }
        }).catch(err => console.error('Copy failed:', err));
    }

    document.getElementById('copyBaseUrlBtn').addEventListener('click', function () {
        copyText(BASE_URL, this, 'Copied');
    });

    document.querySelectorAll('.copy-endpoint-btn').forEach(button => {
        button.addEventListener('click', function () {
            copyText(this.dataset.endpoint, this, 'Copied');
        });
    });

    function getStoredToken() {
        return localStorage.getItem(TOKEN_STORAGE_KEY) || '';
    }

    function setStoredToken(token) {
        localStorage.setItem(TOKEN_STORAGE_KEY, token);
        updateStoredTokenUI();
    }

    function clearStoredToken() {
        localStorage.removeItem(TOKEN_STORAGE_KEY);
        updateStoredTokenUI();
        document.querySelectorAll('.try-token').forEach(input => {
            input.value = '';
        });
    }

    function maskToken(token) {
        if (!token) return 'No token stored yet.';
        if (token.length <= 30) return token;
        return token.substring(0, 20) + ' ... ' + token.substring(token.length - 12);
    }

    function updateStoredTokenUI() {
        const token = getStoredToken();
        const sidebarState = document.getElementById('sidebarTokenState');
        const storedTokenBox = document.getElementById('storedTokenBox');

        sidebarState.textContent = token ? 'JWT token saved in localStorage.' : 'No JWT token saved yet.';
        storedTokenBox.textContent = token ? token : 'No token stored yet.';
    }

    document.getElementById('clearStoredTokenBtn').addEventListener('click', clearStoredToken);

    document.getElementById('copyStoredTokenBtn').addEventListener('click', function () {
        const token = getStoredToken();
        if (!token) return;
        copyText(token, this, 'Token Copied');
    });

    document.getElementById('useStoredTokenBtn').addEventListener('click', function () {
        const token = getStoredToken();
        if (!token) return;

        document.querySelectorAll('.try-token').forEach(input => {
            input.value = token;
        });

        const original = this.textContent;
        this.textContent = 'Token Loaded';
        setTimeout(() => this.textContent = original, 1400);
    });

    function getHistory() {
        try {
            return JSON.parse(localStorage.getItem(HISTORY_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveHistory(history) {
        localStorage.setItem(HISTORY_STORAGE_KEY, JSON.stringify(history));
        renderHistory();
    }

    function addHistoryItem(item) {
        const history = getHistory();
        history.unshift(item);
        if (history.length > 10) history.pop();
        saveHistory(history);
    }

    function renderHistory() {
        const historyList = document.getElementById('historyList');
        const history = getHistory();

        if (!history.length) {
            historyList.innerHTML = '<p class="muted">No requests stored yet.</p>';
            return;
        }

        historyList.innerHTML = history.map(item => `
            <div class="history-item">
                <div class="history-item-head">
                    <div>
                        <strong>${item.method}</strong> <span class="muted">${item.status}</span>
                    </div>
                    <div class="muted">${item.time}</div>
                </div>
                <div class="history-item-url">${item.url}</div>
            </div>
        `).join('');
    }

    document.getElementById('clearHistoryBtn').addEventListener('click', () => {
        localStorage.removeItem(HISTORY_STORAGE_KEY);
        renderHistory();
    });

    function setResponseMeta(container, { status = '-', ok = null, time = '-', method = '-' }) {
        const statusEl = container.querySelector('.try-status');
        const methodEl = container.querySelector('.try-method');
        const timeEl = container.querySelector('.try-time');

        statusEl.className = 'pill try-status';
        if (ok === true) statusEl.classList.add('ok');
        if (ok === false) statusEl.classList.add('error');

        statusEl.textContent = `Status: ${status}`;
        methodEl.textContent = `Method: ${method}`;
        timeEl.textContent = `Time: ${time}`;
    }

    function prettyPrint(value) {
        if (typeof value === 'string') return value;
        return JSON.stringify(value, null, 2);
    }

    function getEndpointContainer(button) {
        return button.closest('.endpoint-tab-content');
    }

    async function sendEndpointRequest(endpointKey, uiContainer) {
        const endpoint = endpointMap[endpointKey];
        const url = BASE_URL + endpoint.path;
        const startedAt = performance.now();

        const responseBox = uiContainer.querySelector('.try-response');
        const tokenInput = uiContainer.querySelector('.try-token');
        const bodyInput = uiContainer.querySelector('.try-body');

        const headers = {
            'Content-Type': 'application/json'
        };

        const tokenValue = tokenInput ? tokenInput.value.trim() : '';
        if (tokenValue) {
            headers['Authorization'] = `Bearer ${tokenValue}`;
        }

        const options = {
            method: endpoint.method,
            headers
        };

        if (endpoint.method !== 'GET') {
            try {
                const parsed = JSON.parse(bodyInput.value || '{}');
                options.body = JSON.stringify(parsed);
            } catch (error) {
                responseBox.textContent = 'Invalid JSON in request body: ' + error.message;
                setResponseMeta(uiContainer, {
                    status: 'Invalid JSON',
                    ok: false,
                    time: '-',
                    method: endpoint.method
                });
                return;
            }
        }

        responseBox.textContent = 'Sending request...';

        try {
            const res = await fetch(url, options);
            const elapsed = `${Math.round(performance.now() - startedAt)} ms`;

            let data;
            const contentType = res.headers.get('content-type') || '';
            if (contentType.includes('application/json')) {
                data = await res.json();
            } else {
                data = await res.text();
            }

            responseBox.textContent = prettyPrint(data);

            setResponseMeta(uiContainer, {
                status: `${res.status} ${res.statusText}`,
                ok: res.ok,
                time: elapsed,
                method: endpoint.method
            });

            if (endpointKey === 'login' && data && typeof data === 'object' && data.token) {
                setStoredToken(data.token);

                document.querySelectorAll('.try-token').forEach(input => {
                    if (!input.value.trim()) input.value = data.token;
                });
            }

            addHistoryItem({
                method: endpoint.method,
                status: `${res.status} ${res.statusText}`,
                url,
                time: new Date().toLocaleString()
            });
        } catch (error) {
            const elapsed = `${Math.round(performance.now() - startedAt)} ms`;
            responseBox.textContent = `Request error: ${error.message}`;

            setResponseMeta(uiContainer, {
                status: 'Request Failed',
                ok: false,
                time: elapsed,
                method: endpoint.method
            });

            addHistoryItem({
                method: endpoint.method,
                status: 'Request Failed',
                url,
                time: new Date().toLocaleString()
            });
        }
    }

    document.querySelectorAll('.try-btn').forEach(button => {
        button.addEventListener('click', () => {
            const endpointKey = button.dataset.endpointKey;
            const container = getEndpointContainer(button);
            sendEndpointRequest(endpointKey, container);
        });
    });

    document.querySelectorAll('.format-btn').forEach(button => {
        button.addEventListener('click', () => {
            const endpointKey = button.dataset.endpointKey;
            const bodyInput = document.querySelector(`.try-body[data-endpoint-key="${endpointKey}"]`);
            if (!bodyInput || !bodyInput.value.trim()) return;

            try {
                const parsed = JSON.parse(bodyInput.value);
                bodyInput.value = JSON.stringify(parsed, null, 2);

                const original = button.textContent;
                button.textContent = 'Formatted';
                setTimeout(() => button.textContent = original, 1200);
            } catch (error) {
                const container = getEndpointContainer(button);
                const responseBox = container.querySelector('.try-response');
                responseBox.textContent = 'Invalid JSON: ' + error.message;
                setResponseMeta(container, {
                    status: 'Invalid JSON',
                    ok: false,
                    time: '-',
                    method: endpointMap[endpointKey].method
                });
            }
        });
    });

    window.addEventListener('load', () => {
        initScrollSpy();
        initTabs();
        updateStoredTokenUI();
        renderHistory();

        const token = getStoredToken();
        if (token) {
            document.querySelectorAll('.try-token').forEach(input => {
                input.value = token;
            });
        }

        document.querySelectorAll('.endpoint-tab-content').forEach(content => {
            const methodText = content.querySelector('.try-method');
            if (methodText) {
                const endpointId = content.id.split('-')[0];
                setResponseMeta(content, {
                    status: '-',
                    ok: null,
                    time: '-',
                    method: endpointMap[endpointId].method
                });
            }
        });
    });
</script>
</body>
</html>